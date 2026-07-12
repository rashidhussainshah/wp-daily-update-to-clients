<?php

namespace App\utils\traits;

use App\Jobs\EmailsHandlerJob;
use Illuminate\Support\Facades\Auth;

trait EmailTrait
{
    /**
     * @param $data
     * @param bool $updateReq
     * @return void
     */
    public function sendEmail($data, bool $updateReq = false): void
    {
        EmailsHandlerJob::dispatch([
            'mail_name' => 'DeveloperPaymentRequest',
            'to' => $updateReq ? $data->developer->email : Auth::user()->email,
            'id' => $data->id,
            'subject' => $updateReq ? 'Your Payment Request Approved' . $data->title : 'Payment Request of '. Auth::user()->name,
            'developer_name' => $updateReq ? $data->developer->name : Auth::user()->name,
            'project_name' => $data->project->name ?? '',
            'project_target_title' => $data->projectTarget->title ?? '',
            'status' => $data->status, // user payment status will send in this key
            'total_earning' => $data->total_earning,
            'dev_earning' => $data->dev_earning,
            'payable' => $data->payable,
            'paid' => $data->paid,
            'client_source' => $data->client_source,
            'current_currency_rate' => $data->currency_current_rate,
            'fee' => $data->fee,
            'notes' => $data->notes,
            'is_payment_approve_req' => $updateReq,
            'attachments' => json_decode($data['attachments'], true),
        ]);
    }
    public function sendPaymentReqApproveEmail($data): void
    {
        EmailsHandlerJob::dispatch([
            'mail_name' => 'DeveloperPaymentRequest',
            'to' => $data->developer->email,
            'id' => $data->id,
            'subject' => 'Your Payment Request Approved' . $data->title,
            'developer_name' => $data->developer->name,
            'project_name' => $data->project->name ?? '',
            'project_target_title' => $data->projectTarget->title ?? '',
            'status' => $data->status, // user payment status will send in this key
            'total_earning' => $data->total_earning,
            'dev_earning' => $data->dev_earning,
            'payable' => $data->payable,
            'paid' => $data->paid,
            'client_source' => $data->client_source,
            'currency_current_rate' => $data->currency_current_rate,
            'fee' => $data->fee,
            'notes' => $data->notes,
            'is_payment_approve_req' => true,
        ]);
    }
}
