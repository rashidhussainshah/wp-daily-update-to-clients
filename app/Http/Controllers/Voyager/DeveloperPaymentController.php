<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use App\Jobs\EmailsHandlerJob;
use App\Models\Project;
use App\Models\ProjectTarget;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeveloperPaymentController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    /**
     * Override store method to send email before store
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $project = Project::find($request->project_id);
        $pt = ProjectTarget::find($request->project_target_id);
        EmailsHandlerJob::dispatch([
            'mail_name' => 'DeveloperPaymentRequest',
            'to' => Auth::user()->email,
            'developer_name' => Auth::user()->name,
            'project_name' => $project->name,
            'project_target_title' => $pt->title,
            'project_target_status' => $pt->status,
            'total_earning' => $request->total_earning,
            'dev_earning' => $request->dev_earning,
            'payable' => $request->payable,
            'paid' => $request->paid,
            'currency_current_rate' => $request->currency_current_rate,
            'fee' => $request->fee,
            'notes' => $request->notes,
        ]);

        return parent::store($request);
    }
}
