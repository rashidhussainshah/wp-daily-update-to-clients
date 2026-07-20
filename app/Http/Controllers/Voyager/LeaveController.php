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
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Spatie\SlackAlerts\Facades\SlackAlert;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Facades\Voyager;
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
        if ($requestedConsecutiveDays > $maxConsecutiveDays) {
            // Flag this request for COO approval, but do not block storing
            $request->merge(['coo_required' => true]);
        }

        // Load existing leaves for current user (excluding soft-deleted by default)
        $leaves = Leave::where('user_id', $user->id)->get();

        // Check for duplicate/overlapping leaves
        $overlappingLeaves = [];
        foreach ($leaves as $l) {
            $lStart = Carbon::parse($l->start_date);
            $lEnd = $l->end_date ? Carbon::parse($l->end_date) : $lStart->copy();

            // Check if dates overlap: two date ranges overlap if one starts before the other ends
            $hasOverlap = !($end->lt($lStart) || $start->gt($lEnd));

            if ($hasOverlap) {
                $dateRange = $lStart->format('M d, Y');
                if (!$lStart->isSameDay($lEnd)) {
                    $dateRange .= ' to ' . $lEnd->format('M d, Y');
                }
                $overlappingLeaves[] = "• {$dateRange}" . ($l->reason ? " - {$l->reason}" : "");
            }
        }

        // If there are overlapping leaves, prevent duplicate submission
        if (!empty($overlappingLeaves)) {
            $requestedRange = $start->format('M d, Y');
            if (!$start->isSameDay($end)) {
                $requestedRange .= ' to ' . $end->format('M d, Y');
            }
            $overlappingInfo = "\n\nConflicting leave(s):\n" . implode("\n", $overlappingLeaves);
            return Redirect::back()
                ->withErrors(['duplicate_leave' => "You already have leave(s) that overlap with your requested dates ({$requestedRange}).{$overlappingInfo}"])
                ->withInput();
        }

        // Monthly check: count existing leave requests per month overlapped by the request
        $quotaExceeded = false;
        $quotaExceededMessage = '';
        $cursor = $start->copy()->startOfMonth();
        $endMonthStart = $end->copy()->startOfMonth();
        while ($cursor->lte($endMonthStart)) {
            $monthStart = $cursor->copy();
            $monthEnd = $cursor->copy()->endOfMonth();

            // Count existing leave entries overlapping this month
            $existingRequestsThisMonth = 0;
            $existingLeavesDetails = [];
            foreach ($leaves as $l) {
                $lStart = Carbon::parse($l->start_date);
                $lEnd = $l->end_date ? Carbon::parse($l->end_date) : $lStart->copy();
                $overlapsMonth = !$lEnd->lt($monthStart) && !$lStart->gt($monthEnd);
                if ($overlapsMonth) {
                    $existingRequestsThisMonth++;
                    // Collect leave details for display
                    $dateRange = $lStart->format('M d, Y');
                    if (!$lStart->isSameDay($lEnd)) {
                        $dateRange .= ' to ' . $lEnd->format('M d, Y');
                    }
                    $existingLeavesDetails[] = "• {$dateRange}" . ($l->reason ? " - {$l->reason}" : "");
                }
            }

            // Check if adding this new request would exceed the monthly limit
            if ($maxMonthlyRequests > 0 && $existingRequestsThisMonth >= $maxMonthlyRequests) {
                $quotaExceeded = true;
                $monthLabel = $monthStart->format('F Y');
                $leavesInfo = !empty($existingLeavesDetails)
                    ? "\n\nYour existing leaves in {$monthLabel}:\n" . implode("\n", $existingLeavesDetails)
                    : "";
                $quotaExceededMessage = "Leave quota exceeded for {$monthLabel}. Allowed: {$maxMonthlyRequests} request(s) per month. You have already submitted {$existingRequestsThisMonth}.{$leavesInfo}\n\nThis will be recorded as extra leave beyond your monthly quota.";
            }

            $cursor->addMonth()->startOfMonth();
        }

        // Send Slack notification
        $message = "$user->name has requested leave from $startDate to " . ($endDate ?: $startDate) . " for the following reason: $reason.";
        if ($request->boolean('coo_required')) {
            $message .= ' (COO approval required)';
        }
        if ($quotaExceeded) {
            $message .= ' ⚠️ NOTE: Extra leave - Monthly quota exceeded';
        }
        $slackWebhookUrl = env('LOG_EOD_SLACK_WEBHOOK_URL') ?? 'https://hooks.slack.com/services/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';
        try {
            SlackAlert::to($slackWebhookUrl)->message(strip_tags($message));
        } catch (\Throwable $e) {
            Log::warning('Leave request Slack notification failed: ' . $e->getMessage());
        }

        // Perform Voyager store here to customize the flash message
        $slug = $this->getSlug($request);
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        // Validate fields with ajax
        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());

        event(new BreadDataAdded($dataType, $data));

        if (!$request->has('_tagging')) {
            if (auth()->user()->can('browse', $data)) {
                $redirect = redirect()->route("voyager.{$dataType->slug}.index");
            } else {
                $redirect = redirect()->back();
            }

            $baseMessage = __('voyager::generic.successfully_added_new')." {$dataType->getTranslatedAttribute('display_name_singular')}";
            $alertType = 'success';

            if ($quotaExceeded) {
                $baseMessage .= ' — ⚠️ WARNING: ' . $quotaExceededMessage;
                $alertType = 'warning';
            } elseif ($request->boolean('coo_required')) {
                $baseMessage .= ' — Warning: COO approval is required for this leave and must be approved by Ayub.';
                $alertType = 'warning';
            }

            return $redirect->with([
                'message'    => $baseMessage,
                'alert-type' => $alertType,
            ]);
        } else {
            return response()->json(['success' => true, 'data' => $data]);
        }
    }

    /**
     * Approve a leave request that required COO approval.
     */
    public function approve(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || (int) $user->id !== (int) User::AYUB_USER_ID) {
            return Redirect::back()->withErrors(['auth' => 'Only COO (Ayub) can approve leaves.']);
        }
        $leave = Leave::findOrFail($id);
        // Only allow approving those marked as requiring COO approval
        if (!(bool) ($leave->coo_required ?? false)) {
            return Redirect::back()->withErrors(['leave' => 'This leave does not require COO approval.']);
        }
        // Update approval fields
        $leave->coo_required = false;
        $leave->coo_approved_at = now();
        $leave->coo_approved_by = $user->id;
        $leave->save();

        // Optional: notify via Slack
        $startDate = $leave->start_date;
        $endDate = $leave->end_date ?: $startDate;
        $message = "COO Approval: {$user->name} approved leave #{$leave->id} ({$startDate} to {$endDate}) for user ID {$leave->user_id}.";
        $slackWebhookUrl = env('LOG_EOD_SLACK_WEBHOOK_URL');
        if ($slackWebhookUrl) {
            try {
                SlackAlert::to($slackWebhookUrl)->message(strip_tags($message));
            } catch (\Throwable $e) {
                Log::warning('Leave approval Slack notification failed: ' . $e->getMessage());
            }
        }

        return Redirect::back()->with(['message' => 'Leave approved successfully.', 'alert-type' => 'success']);
    }
}
