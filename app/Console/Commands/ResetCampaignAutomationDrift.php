<?php

namespace App\Console\Commands;

use App\Models\CampaignAutomation;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResetCampaignAutomationDrift extends Command
{
    protected $signature   = 'automations:reset-drift
                            {--id= : Only check/reset this automation ID}
                            {--dry-run : Show what would change without saving}
                            {--force : Skip the confirmation prompt}';
    protected $description = 'Recompute next_run_at from send_time for automations left mid-batch or drifted by the old scheduling bug';

    public function handle(): int
    {
        $query = CampaignAutomation::whereIn('status', ['active', 'paused']);

        if ($id = $this->option('id')) {
            $query->where('id', $id);
        }

        $candidates = $query->get()->filter(function (CampaignAutomation $auto) {
            return $auto->emails_sent_in_batch > 0
                || !$auto->next_run_at
                || $auto->next_run_at->format('H:i:s') !== $auto->send_time;
        });

        if ($candidates->isEmpty()) {
            $this->info('No drifted automations found.');
            return 0;
        }

        $rows = $candidates->map(function (CampaignAutomation $auto) {
            $corrected = $this->correctedNextRun($auto);

            return [
                $auto->id,
                $auto->name,
                $auto->frequency,
                $auto->send_time,
                $auto->next_run_at?->toDateTimeString() ?? 'null',
                $auto->emails_sent_in_batch,
                $corrected?->toDateTimeString() ?? 'null',
            ];
        });

        $this->table(
            ['ID', 'Name', 'Frequency', 'Send Time', 'Current next_run_at', 'Sent in Batch', 'Corrected next_run_at'],
            $rows
        );

        if ($this->option('dry-run')) {
            $this->warn('[Dry run] No changes saved.');
            return 0;
        }

        if (!$this->option('force') && !$this->confirm('Apply the corrected schedule shown above?')) {
            $this->warn('Aborted — no changes saved.');
            return 0;
        }

        foreach ($candidates as $auto) {
            $corrected = $this->correctedNextRun($auto);

            $auto->update([
                'next_run_at'          => $corrected,
                'emails_sent_in_batch' => 0,
            ]);

            Log::info("[Automations][ResetDrift] #{$auto->id} \"{$auto->name}\": next_run_at reset to " . ($corrected?->toDateTimeString() ?? 'null'));
            $this->info("Automation #{$auto->id} \"{$auto->name}\": next_run_at → " . ($corrected?->toDateTimeString() ?? 'null'));
        }

        return 0;
    }

    private function correctedNextRun(CampaignAutomation $auto): ?Carbon
    {
        if ($auto->frequency === 'once') {
            // A stalled 'once' automation only shows up here because it's mid-batch
            // (already overdue) — the fixed command will finish it on the next tick.
            return now();
        }

        [$h, $m, $s] = array_pad(explode(':', $auto->send_time), 3, 0);
        $h = (int) $h;
        $m = (int) $m;
        $s = (int) $s;

        // If it already ran today (however drifted), don't let the reset make it
        // fire a second time today at the "correct" time — push to the next cycle.
        $ranToday = $auto->last_run_at && $auto->last_run_at->isToday();

        return match ($auto->frequency) {
            'daily'   => $this->nextDaily($h, $m, $s, $ranToday),
            'weekly'  => $this->nextWeekly($auto, $h, $m, $s, $ranToday),
            'monthly' => $this->nextMonthly($auto, $h, $m, $s, $ranToday),
            default   => null,
        };
    }

    private function nextDaily(int $h, int $m, int $s, bool $ranToday): Carbon
    {
        $slot = now()->copy()->setTime($h, $m, $s);

        return (!$ranToday && $slot->isFuture()) ? $slot : $slot->addDay();
    }

    private function nextWeekly(CampaignAutomation $auto, int $h, int $m, int $s, bool $ranToday): Carbon
    {
        // start_date is the real anchor day-of-week — send_day_of_week is a cosmetic
        // form field never consulted by buildFirstRun()/calculateNextRun().
        $dow  = $auto->start_date->dayOfWeek;
        $slot = now()->copy()->setTime($h, $m, $s);

        if (!$ranToday && now()->dayOfWeek === $dow && $slot->isFuture()) {
            return $slot;
        }

        return now()->copy()->next($dow)->setTime($h, $m, $s);
    }

    private function nextMonthly(CampaignAutomation $auto, int $h, int $m, int $s, bool $ranToday): Carbon
    {
        // start_date's day-of-month is the real anchor, same reasoning as nextWeekly().
        $anchorDay = $auto->start_date->day;
        $thisMonth = now()->copy()->startOfMonth()
            ->addDays(min($anchorDay, now()->daysInMonth) - 1)
            ->setTime($h, $m, $s);

        if (!$ranToday && $thisMonth->isFuture()) {
            return $thisMonth;
        }

        $next = now()->copy()->startOfMonth()->addMonthNoOverflow();
        $day  = min($anchorDay, $next->daysInMonth);

        return $next->addDays($day - 1)->setTime($h, $m, $s);
    }
}
