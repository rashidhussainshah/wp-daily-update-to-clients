<?php

namespace App\Http\Controllers;

use App\Jobs\EmailsHandlerJob;
use App\Models\Checkin;
use App\Models\EodConfiguration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\SlackAlerts\Facades\SlackAlert;

class CheckinController extends Controller
{
    public function storeCheckin(Request $request)
    {
        date_default_timezone_set('Asia/Karachi');
        Checkin::create([
            'developer_id' => Auth::id(),
            'checkin_at' => now(),
            'today_work_plan' => $request->input('today_work_plan'),
        ]);
        // Prepare the message with the user name, today's plan, and the current time
        $user = Auth::user();
        $todayWorkPlan = $request->input('today_work_plan');
        $currentTime = Carbon::now()->format('h:i A');

        // Create the block payload for Slack
        $blocks = [
            [
                'type' => 'section',
                'text' => [
                    'type' => 'mrkdwn',
                    'text' => "*Check-in done for " . $user->name . " at " . $currentTime . "*\n\n*Today's Plan:*\n" . $todayWorkPlan,
                ],
            ],
        ];

        // Send the message to Slack using blocks
        SlackAlert::blocks($blocks);
        return redirect()->back()->with('success', 'Check-in message sent to Slack!');
    }

    public function storeCheckout(Request $request)
    {
        $checkin = Checkin::where('developer_id', Auth::id())
            ->whereNull('checkout_at')
            ->whereDate('checkin_at', Carbon::today())
            ->latest()->first();

        if (!$checkin) {
            return redirect()->back()->with('error', 'No check-in information found for today. Please check-in first.');
        }
        $tomorrowWorkPlan = $request->input('tomorrow_work_plan');

        $checkin->update([
            'checkout_at' => now(),
            'end_of_day_report' => $request->input('end_of_day_report'),
            'tomorrow_work_plan' => $tomorrowWorkPlan,
        ]);

        $user = Auth::user();
        $endOfDayReport = $request->input('end_of_day_report');
        $currentTime = Carbon::now()->format('h:i A');

        // Calculate total office hours based on user type
        $totalOfficeHours = $user->part_time ? 4.5 : 9;
        $checkinAt = Carbon::parse($checkin->checkin_at);
        $checkoutAt = Carbon::parse($checkin->checkout_at);
        $hoursSpent = $checkinAt->diffInHours($checkoutAt);

        // Calculate shortfall in hours
        $hoursShort = $totalOfficeHours - $hoursSpent;

        // Add short time with message if hours are insufficient
        $message = "*Check-out done for " . $user->name . " at " . $currentTime . "*\n\n*EOD Report:*\n" . $endOfDayReport . "\n\n*Plan for Tomorrow:*\n" . $tomorrowWorkPlan . "\n\n";
        if ($hoursShort > 0) {
            $minutesSpent = $checkinAt->diffInMinutes($checkoutAt) % 60;
            $minutesShort = 60 - $minutesSpent;
            $hoursShort = $hoursShort - 1;

            $message .= "*Short by " . "*" . $hoursShort . " hours and " . $minutesShort . " minutes.*";
        }

        $blocks = [
            [
                'type' => 'section',
                'text' => [
                    'type' => 'mrkdwn',
                    'text' => $message,
                ],
            ],
        ];

        $eod = EodConfiguration::where('developer_id', Auth::id())->latest()->first();
        SlackAlert::to($eod->slack_webhook_url)->blocks($blocks);

//        EmailsHandlerJob::dispatch([
//            'mail_name' => 'EndOfDayReport',
//            'dynamic_eod_content' => $request->email,
//            'plan_for_tomorrow' => $request->plan_for_tomorrow,
//            'developer_name' => Auth::user()->name,
//            'client_name' => $project->eodConfiguration->client->name,
//            'project_name' => $project->name,
//            'signature' => $project->eodConfiguration->signature,
//            'to' => $project->eodConfiguration->client->email,
//            'enable_slack' => $project->eodConfiguration->enable_slack,
//            'is_send_email' => $project->eodConfiguration->is_send_email,
//            'slack_webhook_url' => $project->eodConfiguration->slack_webhook_url,
//            'subject' => $project->eodConfiguration->subject,
//            'cc' => $project->eodConfiguration->cc,
//            'bcc' => $project->eodConfiguration->bcc,
//        ]);
        return redirect()->back()->with('success', 'Check-out message sent to Slack!');
    }
}
