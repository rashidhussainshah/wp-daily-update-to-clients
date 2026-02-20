<?php

namespace App\Http\Controllers;

use App\Jobs\EmailsHandlerJob;
use App\Models\Checkin;
use App\Models\CheckinConfiguration;
use App\Models\EodConfiguration;
use App\Models\Fine;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Exception;
use Spatie\SlackAlerts\Facades\SlackAlert;

/**
 *
 */
class CheckinController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeCheckin(Request $request): RedirectResponse
    {
        try {
            date_default_timezone_set('Asia/Karachi');
            $todayDate = Carbon::today()->toDateString();
            $userId = Auth::id();
            $user = Auth::user();

            // Check if Slack Configuration exists
            $checkinConfig = CheckinConfiguration::where('developer_id', $userId)->first();
            if (!$checkinConfig) {
                return redirect()->back()->with($this->getErrorMsg('No Slack configuration found for the user.'));
            }

            // Prevent duplicate check-ins
            $existingCheckin = Checkin::where('developer_id', $userId)
                ->whereDate('checkin_at', $todayDate)
                ->first();

            if ($existingCheckin) {
                return redirect()->back()->with($this->getErrorMsg('Check-in information already exists for today.'));
            }

            $now = \Carbon\Carbon::now();
            $fineApplied = false;
            $fineAmount = 0;

            $lateCheckinFineEnabled = setting('checkin.late_checkin_fine_enabled', false); // Default false
            $isDevTeam = $user->is_development_team_member;

            // Apply fine only if slot is enabled + dev team member
            if ($lateCheckinFineEnabled && $isDevTeam) {
                // Use user-defined time or default to 09:50
                $checkinTime = $user->checkin_time ?? '10:30';

                $allowedTime = Carbon::parse($todayDate . ' ' . $checkinTime);

                if ($now->greaterThan($allowedTime)) {
                    // Check if the user has leave for today
//                    $hasLeaveToday = Leave::where('user_id', $user->id)
//                        ->whereDate('start_date', '<=', $todayDate)
//                        ->whereDate('end_date', '>=', $todayDate)
//                        ->whereNull('deleted_at')
//                        ->exists();

//                    if (!$hasLeaveToday) {
                        // Apply fine
                        $fineAmount = setting('checkin.late_fine_amount_enabled'); // Voyager setting
                        Fine::create([
                            'user_id' => $user->id,
                            'amount' => $fineAmount,
                            'reason' => 'Late check-in',
                            'note' => 'Fine applied due to late check-in',
                            'date' => $now,
                        ]);

                        $fineApplied = true;
//                    }
                }
            }

            // Create check-in record
            Checkin::create([
                'developer_id' => $userId,
                'checkin_at' => $now,
                'today_work_plan' => $request->input('today_work_plan'),
            ]);

            // Prepare Slack message
            $todayWorkPlan = $request->input('today_work_plan');
            $currentDate = $now->format('Y-m-d');

            $slackMessage = "*Check-in on {$currentDate}*\n\n*Today's Plan:*\n{$todayWorkPlan}\n\n*{$user->name} : {$checkinConfig->designation}*";

            if ($fineApplied) {
                $slackMessage .= "\n\n:warning: *Late Check-in Fine Applied:* Rs. {$fineAmount}";
            }

            $blocks = [
                [
                    'type' => 'section',
                    'text' => [
                        'type' => 'mrkdwn',
                        'text' => $slackMessage,
                    ],
                ],
            ];

            $this->sendTxtToSlack($blocks, $checkinConfig->slack_webhook_url);

            $msg = $fineApplied
                ? "Check-in successful! A fine of Rs. {$fineAmount} was applied due to late check-in."
                : "Check-in successful and sent to Slack!";
            if ($fineApplied) {
                return redirect()->back()->with($this->getSuccessMsg($msg, 'error'));
            } else {
                return redirect()->back()->with($this->getSuccessMsg($msg));
            }

        } catch (Exception $exception) {
            return redirect()->back()->with($this->getErrorMsg($exception->getMessage()));
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeCheckout(Request $request): RedirectResponse
    {
        try {
            $user = Auth::user();

            // Check if the CheckinConfiguration exists for the current user
            $checkinConfig = CheckinConfiguration::where('developer_id', $user->id)->first();
            if (!$checkinConfig) {
                return redirect()->back()->with('error', 'No Slack configuration found for the user.');
            }

            // Fetch check-in record for today
            $checkin = Checkin::where('developer_id', Auth::id())
                ->whereNull('checkout_at')
                ->whereDate('checkin_at', Carbon::today())
                ->latest()->first();

            if (!$checkin) {
                return redirect()->back()->with($this->getErrorMsg('No check-in information found for today. Please check-in first.'));
            }

            // Get work plan input
            $tomorrowWorkPlan = $request->input('tomorrow_work_plan');
            $endOfDayReport = $request->input('end_of_day_report');

            // Calculate time spent in minutes
            $checkinAt = Carbon::parse($checkin->checkin_at);
            $checkoutAt = Carbon::parse(now());
            $totalMinutesSpent = $checkinAt->diffInMinutes($checkoutAt);

            // Determine required work time in minutes
            $currentDay = Carbon::now()->dayOfWeek;
            $weekdayHours = (float) setting('checkin.weekday_hours'); // Convert to float
            $saturdayHours = (float) setting('checkin.saturday_hours'); // Convert to float

            $requiredMinutes = ($currentDay == Carbon::SATURDAY)
                ? $saturdayHours * 60
                : $weekdayHours * 60;
            $requiredMinutes = (int) $requiredMinutes;
            // Calculate remaining time
            $remainingMinutes = $requiredMinutes - $totalMinutesSpent;

            // If user has not worked enough, show an error
            if ($remainingMinutes > 0) {
                $remainingHours = intdiv($remainingMinutes, 60);
                $remainingMinutes = $remainingMinutes % 60;
                $remainingMessage = "You still need to work " . $remainingHours . " hours and " . $remainingMinutes . " minutes. Please complete your office hours before checking out.";
                return redirect()->back()->with($this->getErrorMsg($remainingMessage));
            }

            // If hours are sufficient, update the check-in record with checkout time, EOD report, and work plan
            $checkin->update([
                'checkout_at' => now(),
                'end_of_day_report' => $endOfDayReport,
                'tomorrow_work_plan' => $tomorrowWorkPlan,
            ]);

            // Format the message to be sent to Slack
            $currentDate = Carbon::now()->format('Y-m-d');
            $message = "*Check-out on " . $currentDate . "*\n\n*EOD Report:*\n" . $endOfDayReport . "\n\n*Plan for Tomorrow:*\n" . $tomorrowWorkPlan;

            // Add time spent for non-development team members
            $totalHoursSpent = intdiv($totalMinutesSpent, 60);
            $remainingMinutesForSlack = $totalMinutesSpent % 60;
            if (!$user->is_development_team_member) {
                $message .= "\n\n*Total time spent: " . $totalHoursSpent . " hours and " . $remainingMinutesForSlack . " minutes.*";
            } else {
                // For development team members, calculate short or extra hours
                $totalOfficeMinutes = $user->part_time ? 4.5 * 60 : 9 * 60;
                $minutesShort = $totalOfficeMinutes - $totalMinutesSpent;
                $minutesExtra = $totalMinutesSpent - $totalOfficeMinutes;

                if ($minutesShort > 0) {
                    $message .= "\n\n*Short by " . intdiv($minutesShort, 60) . " hours and " . ($minutesShort % 60) . " minutes.*";
                } elseif ($minutesExtra > 0) {
                    $message .= "\n\n*Extra time worked: " . intdiv($minutesExtra, 60) . " hours and " . ($minutesExtra % 60) . " minutes.*";
                }
            }

            // Add user and designation information
            $message .= "\n\n*" . $user->name . " : " . $checkinConfig->designation . "*";

            // Send the message to Slack
            $blocks = [
                [
                    'type' => 'section',
                    'text' => [
                        'type' => 'mrkdwn',
                        'text' => $message,
                    ],
                ],
            ];

            // Send the message to Slack
        $this->sendTxtToSlack($blocks, $checkinConfig->slack_webhook_url);

            return redirect()->back()->with($this->getSuccessMsg('Check-out message sent to Slack!'));
        } catch (\Exception $exception) {
            return redirect()->back()->with($this->getErrorMsg($exception->getMessage()));
        }
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getYesterdaysPlan(): \Illuminate\Http\JsonResponse
    {
        // Calculate yesterday's date
        $yesterdayDate = now()->subDay()->format('Y-m-d');

        // Query for the check-in of the previous day
        $previousDayCheckin = Checkin::whereDate('checkin_at', $yesterdayDate)
            ->where('developer_id', Auth::id())
            ->latest()
            ->first();

        $yesterdaysWorkPlan = $previousDayCheckin ? $previousDayCheckin->tomorrow_work_plan : '';

        return response()->json([
            'yesterdaysWorkPlan' => $yesterdaysWorkPlan
        ]);
    }
    /**
     * @return mixed
     */
    public function sendTxtToSlack($blocks, $slackWebhookUrl)
    {
        SlackAlert::to($slackWebhookUrl)->blocks($blocks);
    }

    /**
     * @return string[]
     */
    public function getErrorMsg($msg): array
    {
        $data = [
            'message' => $msg,
            'alert-type' => 'error',
        ];
        return $data;
    }

    /**
     * @param $msg
     * @return array
     */
    public function getSuccessMsg($msg, $alertType = 'success'): array
    {
        return [
            'message' => $msg,
            'alert-type' => $alertType,
        ];
    }
}
