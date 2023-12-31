<?php

namespace App\Http\Controllers\Voyager;

use App\Jobs\EmailsHandlerJob;
use Illuminate\Http\Request;

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
            'name' => $request->name,
            'to' => $request->email,
            'password' => $request->password,
        ]);
        return parent::store($request);
    }
}
