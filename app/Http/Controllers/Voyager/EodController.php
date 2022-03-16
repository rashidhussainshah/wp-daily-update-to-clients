<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectTarget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;

/**
 * Eod -> End of Day Email Controller
 */
class EodController extends VoyagerBaseController
{
    /**
     * Get Today Target tasks with configuration
     * to send in email
     * @return JsonResponse
     */
    public function eodContent(): JsonResponse
    {
        $project = Project::with(['targets' => function ($targetQry) {
                                $targetQry->whereDeveloperId(Auth::user()->id);
                                $targetQry->whereHas('tasks', function (Builder $targetTaskQry) {
                                $targetTaskQry->whereDate('created_at', today());
                                });
                            }, 'eodConfiguration', 'targets.tasks'])->first();
        return response()->json(['data'=> $project]);
    }

    /**
     * Override store method to send email before store
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
            dd($request);
//            parent::store();
    }
}
