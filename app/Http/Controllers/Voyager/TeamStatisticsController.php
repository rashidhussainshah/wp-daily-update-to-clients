<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use App\Models\Checkin;
use App\Models\Fine;
use App\Models\Leave;
use App\Models\User;
use App\utils\traits\ResolvesDatePresets;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;

class TeamStatisticsController extends Controller
{
    use ResolvesDatePresets;

    /** Company policy: allowed leave days per user per month. */
    const ALLOWED_LEAVES_PER_MONTH = 2;

    /** Company policy: expected shift length in minutes (9h incl. break). */
    const EXPECTED_SHIFT_MINUTES = 540;

    /** Company policy: unpaid break inside a shift, in minutes. */
    const BREAK_MINUTES = 60;

    /**
     * Administrator statistics for the leaves, fines and checkins modules.
     */
    public function index(Request $request)
    {
        if (!isAdministrator()) {
            abort(403, 'Only administrators can view team statistics.');
        }

        $filters = $this->resolveDateFilters($request->only(['user_id', 'date_from', 'date_to', 'period']));
        $userId = $filters['user_id'] ?? null;
        $from = $filters['date_from'] ?? null;
        $to = $filters['date_to'] ?? null;

        // ---- Leaves (dated by start_date) ----
        $leaves = Leave::query()
            ->when($userId, fn($q, $v) => $q->where('user_id', $v))
            ->when($from, fn($q, $v) => $q->whereDate('start_date', '>=', $v))
            ->when($to, fn($q, $v) => $q->whereDate('start_date', '<=', $v));

        $leaveStats = (clone $leaves)
            ->selectRaw("COUNT(*) as requests,
                COALESCE(SUM(DATEDIFF(COALESCE(end_date, start_date), start_date) + 1),0) as days,
                SUM(management_approval = 'approved') as approved,
                SUM(management_approval = 'pending') as pending,
                SUM(coo_required = 1) as coo_required")
            ->first();

        // Leave-rule compliance: max ALLOWED_LEAVES_PER_MONTH days per user per
        // month. A leave is counted in the month it starts.
        $leavesPerUserMonth = (clone $leaves)
            ->selectRaw("user_id, DATE_FORMAT(start_date, '%Y-%m') as month,
                COALESCE(SUM(DATEDIFF(COALESCE(end_date, start_date), start_date) + 1),0) as days")
            ->groupBy('user_id', 'month')
            ->get();

        $leaveCompliance = $leavesPerUserMonth->groupBy('user_id')->map(function ($rows) {
            $months = $rows->count();
            $days = (float) $rows->sum('days');
            $overMonths = $rows->filter(fn($r) => $r->days > self::ALLOWED_LEAVES_PER_MONTH)->count();
            return (object) [
                'months' => $months,
                'days' => $days,
                'avg_per_month' => $months ? round($days / $months, 1) : 0,
                'max_month_days' => (float) $rows->max('days'),
                'over_months' => $overMonths,
                'following' => $overMonths === 0,
            ];
        });
        $followingCount = $leaveCompliance->where('following', true)->count();
        $exceedingCount = $leaveCompliance->where('following', false)->count();
        $avgLeavePerUserMonth = $leaveCompliance->count()
            ? round($leaveCompliance->avg('avg_per_month'), 1)
            : 0;

        $leavesByUser = (clone $leaves)
            ->selectRaw("user_id, COUNT(*) as requests,
                COALESCE(SUM(DATEDIFF(COALESCE(end_date, start_date), start_date) + 1),0) as days,
                SUM(management_approval = 'approved') as approved,
                SUM(management_approval = 'pending') as pending")
            ->groupBy('user_id')->orderByDesc('days')->with('user:id,name')->get();

        // ---- Fines (dated by fine date, falling back to created_at) ----
        $fines = Fine::query()
            ->when($userId, fn($q, $v) => $q->where('user_id', $v))
            ->when($from, fn($q, $v) => $q->whereRaw('DATE(COALESCE(date, created_at)) >= ?', [$v]))
            ->when($to, fn($q, $v) => $q->whereRaw('DATE(COALESCE(date, created_at)) <= ?', [$v]));

        $fineStats = (clone $fines)
            ->selectRaw("COUNT(*) as fines, COALESCE(SUM(amount),0) as amount,
                COALESCE(SUM(paid),0) as paid,
                SUM(status = 'deducted') as deducted_count,
                COALESCE(SUM(CASE WHEN status = 'deducted' THEN amount ELSE 0 END),0) as deducted_amount,
                COALESCE(SUM(CASE WHEN status != 'deducted' THEN amount ELSE 0 END),0) as pending_amount")
            ->first();

        $finesByUser = (clone $fines)
            ->selectRaw("user_id, COUNT(*) as fines, COALESCE(SUM(amount),0) as amount,
                COALESCE(SUM(paid),0) as paid,
                COALESCE(SUM(CASE WHEN status = 'deducted' THEN amount ELSE 0 END),0) as deducted_amount")
            ->groupBy('user_id')->orderByDesc('amount')->with('user:id,name')->get();

        // ---- Checkins (dated by checkin time, falling back to created_at) ----
        $checkins = Checkin::query()
            ->when($userId, fn($q, $v) => $q->where('developer_id', $v))
            ->when($from, fn($q, $v) => $q->whereRaw('DATE(COALESCE(checkin_at, created_at)) >= ?', [$v]))
            ->when($to, fn($q, $v) => $q->whereRaw('DATE(COALESCE(checkin_at, created_at)) <= ?', [$v]));

        // Average only plausible shifts (0-18h); corrupt pairs (e.g. a checkout
        // dated years before its checkin) would otherwise poison the average.
        $workedMinutes = "CASE WHEN checkin_at IS NOT NULL AND checkout_at IS NOT NULL
                AND TIMESTAMPDIFF(MINUTE, checkin_at, checkout_at) BETWEEN 0 AND 1080
            THEN TIMESTAMPDIFF(MINUTE, checkin_at, checkout_at) END";

        // Policy: EXPECTED_SHIFT_MINUTES shift (9h) including BREAK_MINUTES (1h)
        // break, so net working target is 8h. Full day = shift >= 9h.
        $expected = self::EXPECTED_SHIFT_MINUTES;
        $break = self::BREAK_MINUTES;

        $checkinStats = (clone $checkins)
            ->selectRaw("COUNT(*) as checkins,
                COUNT(DISTINCT developer_id) as users,
                SUM(checkin_at IS NOT NULL AND checkout_at IS NULL) as missing_checkout,
                SEC_TO_TIME(AVG(TIME_TO_SEC(TIME(checkin_at)))) as avg_in,
                SEC_TO_TIME(AVG(TIME_TO_SEC(TIME(checkout_at)))) as avg_out,
                ROUND(AVG({$workedMinutes}) / 60, 1) as avg_hours,
                ROUND((AVG({$workedMinutes}) - {$break}) / 60, 1) as avg_net_hours,
                SUM(({$workedMinutes}) >= {$expected}) as full_days,
                SUM(({$workedMinutes}) < {$expected}) as short_days")
            ->first();

        $checkinsByUser = (clone $checkins)
            ->selectRaw("developer_id, COUNT(*) as days,
                SUM(checkin_at IS NOT NULL AND checkout_at IS NULL) as missing_checkout,
                SEC_TO_TIME(AVG(TIME_TO_SEC(TIME(checkin_at)))) as avg_in,
                SEC_TO_TIME(AVG(TIME_TO_SEC(TIME(checkout_at)))) as avg_out,
                ROUND(AVG({$workedMinutes}) / 60, 1) as avg_hours,
                ROUND((AVG({$workedMinutes}) - {$break}) / 60, 1) as avg_net_hours,
                SUM(({$workedMinutes}) >= {$expected}) as full_days,
                SUM(({$workedMinutes}) < {$expected}) as short_days")
            ->groupBy('developer_id')->orderByDesc('days')->with('developer:id,name')->get();

        // ---- Monthly chart series (last 12 months unless a date filter narrows it) ----
        $monthWindow = fn($q, $col) => (!$from && !$to)
            ? $q->whereRaw("COALESCE({$col}, created_at) >= ?", [now()->subMonths(11)->startOfMonth()])
            : $q;

        $leavesMonthly = $monthWindow((clone $leaves), 'start_date')
            ->selectRaw("DATE_FORMAT(start_date, '%Y-%m') as month, COALESCE(SUM(DATEDIFF(COALESCE(end_date, start_date), start_date) + 1),0) as val")
            ->groupBy('month')->orderBy('month')->pluck('val', 'month');
        $finesMonthly = $monthWindow((clone $fines), 'date')
            ->selectRaw("DATE_FORMAT(COALESCE(date, created_at), '%Y-%m') as month, COALESCE(SUM(amount),0) as val")
            ->groupBy('month')->orderBy('month')->pluck('val', 'month');
        $checkinsMonthly = $monthWindow((clone $checkins), 'checkin_at')
            ->selectRaw("DATE_FORMAT(COALESCE(checkin_at, created_at), '%Y-%m') as month, COUNT(*) as val")
            ->groupBy('month')->orderBy('month')->pluck('val', 'month');

        $months = collect($leavesMonthly->keys())
            ->merge($finesMonthly->keys())
            ->merge($checkinsMonthly->keys())
            ->unique()->sort()->values();
        $monthly = [
            'labels' => $months,
            'leaves' => $months->map(fn($m) => (float) ($leavesMonthly[$m] ?? 0)),
            'fines' => $months->map(fn($m) => (float) ($finesMonthly[$m] ?? 0)),
            'checkins' => $months->map(fn($m) => (float) ($checkinsMonthly[$m] ?? 0)),
        ];

        $users = User::whereIn('id', collect()
            ->merge(Leave::query()->select('user_id')->distinct()->pluck('user_id'))
            ->merge(Fine::query()->select('user_id')->distinct()->pluck('user_id'))
            ->merge(Checkin::query()->select('developer_id')->distinct()->pluck('developer_id'))
            ->unique())->orderBy('name')->get(['id', 'name']);

        return Voyager::view('voyager::team-statistics.index', compact(
            'filters', 'leaveStats', 'leavesByUser', 'fineStats', 'finesByUser',
            'checkinStats', 'checkinsByUser', 'monthly', 'users',
            'leaveCompliance', 'followingCount', 'exceedingCount', 'avgLeavePerUserMonth'
        ));
    }
}
