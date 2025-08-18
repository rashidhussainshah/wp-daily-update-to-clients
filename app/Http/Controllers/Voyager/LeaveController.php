<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use App\Jobs\EmailsHandlerJob;
use App\Models\Project;
use App\Models\User;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Spatie\SlackAlerts\Facades\SlackAlert;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;

class LeaveController extends VoyagerBaseController
{
    /**
     * Override store method to manage leave limits and send Slack alert before store
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Basic validation for required fields
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'reason' => 'required|string',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $reason = $request->input('reason');

        $start = Carbon::parse($startDate)->startOfDay();
        $end = $endDate ? Carbon::parse($endDate)->startOfDay() : $start->copy();
        if ($end->lt($start)) {
            return Redirect::back()->withErrors(['end_date' => 'End date cannot be before start date.'])->withInput();
        }

        $user = User::find(Auth::user()->id);

        // Settings: maximum number of leave requests allowed per month (simple rule)
        $maxMonthlyRequests = (int) setting('leaves.max_monthly_leaves');
        $maxConsecutiveDays = (int) setting('leaves.total_consecutive_days_leaves', 2);

        // If requested range is longer than 2 consecutive days, require COO approval
        $requestedConsecutiveDays = $start->diffInDays($end) + 1; // inclusive
        if ($requestedConsecutiveDays >= $maxConsecutiveDays) {
            return Redirect::back()
                ->withErrors(['leave_limit' => "Requested leave exceeds $maxConsecutiveDays consecutive day(s). Please contact COO to approve, thanks"])
                ->withInput();
        }

        // Load existing leaves for current user (excluding soft-deleted by default)
//        $leaves = Leave::where('user_id', $user->id)->get();

        // Monthly check: count existing leave requests per month overlapped by the request
//        $cursor = $start->copy()->startOfMonth();
//        $endMonthStart = $end->copy()->startOfMonth();
//        while ($cursor->lte($endMonthStart)) {
//            $monthStart = $cursor->copy();
//            $monthEnd = $cursor->copy()->endOfMonth();
//
//            // Count existing leave entries overlapping this month
//            $existingRequestsThisMonth = 0;
//            foreach ($leaves as $l) {
//                $lStart = Carbon::parse($l->start_date);
//                $lEnd = $l->end_date ? Carbon::parse($l->end_date) : $lStart->copy();
//                $overlapsMonth = !$lEnd->lt($monthStart) && !$lStart->gt($monthEnd);
//                if ($overlapsMonth) {
//                    $existingRequestsThisMonth++;
//                }
//            }
//
//            if ($existingRequestsThisMonth > $maxMonthlyRequests) {
//                $monthLabel = $monthStart->format('F Y');
//                return Redirect::back()
//                    ->withErrors(['leave_limit' => "Monthly leave request limit exceeded for $monthLabel. Allowed: $maxMonthlyRequests request(s) per month. You have already submitted $existingRequestsThisMonth."])
//                    ->withInput();
//            }
//
//            $cursor->addMonth()->startOfMonth();
//        }

        // If within limits, send Slack notification and store
        $message = "$user->name has requested leave from $startDate to " . ($endDate ?: $startDate) . " for the following reason: $reason.";
        $slackWebhookUrl = env('LOG_EOD_SLACK_WEBHOOK_URL') ?? 'https://hooks.slack.com/services/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';
        SlackAlert::to($slackWebhookUrl)->message(strip_tags($message));

        return parent::store($request);
    }
}
