<?php

namespace App\Http\Controllers\Voyager;

use App\Jobs\EmailsHandlerJob;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Eod -> End of Day Email Controller
 */
class VoyagerUserController extends \TCG\Voyager\Http\Controllers\VoyagerUserController
{
    /**
     * Override store method to send email before store
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        EmailsHandlerJob::dispatch([
            'mail_name' => 'UserLoginMail',
            'to' => 'developer@webpenter.com',
            'password' => 'abcd',
            'site_url' => config('app.url'),
        ]);
//        parent::store($request);
    }
}
