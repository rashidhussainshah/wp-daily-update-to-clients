<?php

namespace App\Jobs;

use App\Mail\DeveloperPaymentRequestMail;
use App\Mail\EndOfDayReport;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Spatie\SlackAlerts\Facades\SlackAlert;

class EmailsHandlerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            switch ($this->data['mail_name']) {
                //============== User Emails ==============\\
                case 'EndOfDayReport':
                    $mail = new EndOfDayReport($this->data);
                    if (isset($this->data['cc']) && isset($this->data['bcc'])) {
                        \Mail::to($this->data['to'])->cc(explode(',', $this->data['cc']))
                            ->bcc(explode(',', $this->data['bcc']))->send($mail);
                    } elseif (isset($this->data['cc'])) {
                        \Mail::to($this->data['to'])->cc(explode(',', $this->data['cc']))
                            ->send($mail);
                    } elseif (isset($this->data['bcc'])) {
                        \Mail::to($this->data['to'])->bcc(explode(',', $this->data['bcc']))
                            ->send($mail);
                    } else {
                        \Mail::to($this->data['to'])->send($mail);
                    }
                    $dynamicEodContent = strip_tags($this->data['dynamic_eod_content']);
                    $planForTomorrow = strip_tags($this->data['plan_for_tomorrow']);
                    $msg = $dynamicEodContent . 'Plan For tomorrow:'. $planForTomorrow;

                    Log::channel('slackEODNotificationLog')->info($msg);
//                    if ($this->data['enable_slack']) {
//                        SlackAlert::to($this->data['slack_webhook_url'])->message(strip_tags($this->data['dynamic_eod_content']));
//                    }
                    break;
                case 'DeveloperPaymentRequest':
                    $mail = new DeveloperPaymentRequestMail($this->data);
                    // add payment request in email if need to set email to management
                    $mpre = setting('admin.management_payment_request_email');
                    if ($mpre) {
                        \Mail::to($this->data['to'])->cc($mpre)
                            ->send($mail);
                    } else {
                        \Mail::to($this->data['to'])->send($mail);
                    }
                    break;
                //============== Default ==============\\
                default:
                    \Log::critical('EmailsHandlerJob ('.$this->data['mail_name'].'): No matching email found');
                    break;
            }
        } catch (Exception $exception) {
                Log::critical($exception->getMessage());
        }
    }
}
