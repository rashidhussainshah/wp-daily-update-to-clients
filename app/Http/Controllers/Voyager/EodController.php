<?php

namespace App\Http\Controllers\Voyager;

use App\Jobs\EmailsHandlerJob;
use App\Mail\EndOfDayReport;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Eod -> End of Day Email Controller
 */
class EodController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    /**
     * Get Today Target tasks with configuration
     * to send in email
     * @return JsonResponse
     */
    public function eodContent(): JsonResponse
    {
        $project = Project::with(['targets' => function ($targetQry) {
                                $targetQry->whereHas('tasks');
                            }, 'eodConfiguration' => function($eodConfQry) {
                                $eodConfQry->whereDeveloperId(Auth::user()->id);
                                }, 'targets.tasks' => function ($query)
                                {
                                    $query->today();
                                }])->find(4);
        return response()->json(['data'=> $project]);
    }

    /**
     * Override store method to send email before store
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {

        $project = Project::find($request->project_id);
        $vError = false;
        if (!isset($project->eodConfiguration)) {
                $vErrorMessage = __('eod.configuration_not_found');
                $vError = true;
        }
        elseif (!isset($project->eodConfiguration->client)) {
            $vErrorMessage = __('eod.client_not_found');
                $vError = true;
        }
        if ($vError) {
            return redirect()->back()->with([
                'message'    => $vErrorMessage,
                'alert-type' => 'error',
            ]);
        }

        EmailsHandlerJob::dispatch([
            'mail_name' => 'EndOfDayReport',
            'dynamic_eod_content' => $request->email,
            'to' => $project->eodConfiguration->client->email,
            'subject' => $project->eodConfiguration->subject,
            'cc' => $project->eodConfiguration->cc,
            'bcc' => $project->eodConfiguration->bcc,
        ]);

        return parent::store($request);
    }
}
