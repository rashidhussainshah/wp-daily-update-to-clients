<?php

namespace App\Http\Controllers\Voyager;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;

class CheckinConfigurationController extends VoyagerBaseController
{
    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'developer_id' => 'required|exists:users,id|unique:checkin_configurations,developer_id',
            'slack_webhook_url' => 'required|url',
            'designation' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        return parent::store($request);
    }

}
