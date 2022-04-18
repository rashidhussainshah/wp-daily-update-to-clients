<?php

namespace App\Jobs;

use App\Mail\EndOfDayReport;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
                    \Mail::to($this->data['to'])->cc(explode(',', $this->data['cc']))
                        ->bcc(explode(',', $this->data['bcc']))->send($mail);
                    break;
                //============== Default ==============\\
                default:
                    \Log::info('EmailsHandlerJob ('.$this->data['mail_name'].'): No matching email found');
                    break;
            }
        } catch (Exception $exception) {
            $log = "EmailsHandlerJob ErrorMessage => " . $exception;
            \Log::info($log);
        }
    }
}
