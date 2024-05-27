<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use App\Jobs\EmailsHandlerJob;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\SlackAlerts\Facades\SlackAlert;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;

class LeaveController extends VoyagerBaseController
{
    /**
     * Override store method to send email before store
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $reason = $request->input('reason');
        $user = User::find($request->input('user_id'));
        $message = "$user->name has requested leave from $startDate to $endDate for the following reason: $reason.";
        // Retrieve the Slack webhook URL from the environment
        $slackWebhookUrl = env('LOG_EOD_SLACK_WEBHOOK_URL') ?? 'https://hooks.slack.com/services/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';
        SlackAlert::to($slackWebhookUrl)->message(strip_tags($message));
        return parent::store($request);
    }
}
