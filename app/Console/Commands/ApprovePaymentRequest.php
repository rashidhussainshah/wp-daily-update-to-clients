<?php

namespace App\Console\Commands;

use App\Models\UserPayment;
use App\utils\traits\EmailTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ApprovePaymentRequest extends Command
{
    use EmailTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:approve {user_id} {ccr?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pass user id to approve payment request of a user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('The command was successful!');
        $upq = UserPayment::whereDeveloperId($this->argument('user_id'))->approved()->get();
        if (!$upq->isEmpty()) {
            $this->info('processing started');
            foreach ($upq as $pq) {
                $pq->paid = $pq->payable;
                $pq->update_by_command = true;
                $pq->status = UserPayment::APPROVED_STATUS;

                // Retrieve the existing text from the specific field
                $oldText = $pq->notes;
                // New string to append
                $newString = "Approved through command";
                // Check if the specific field already contains text
                if (!empty($oldText)) {
                    // Add a line break and append the new string
                    $newText = $oldText . PHP_EOL . $newString;
                } else {
                    // Set the new string as the initial text
                    $newText = $newString;
                }
                // Save the updated text back into the specific field
                $pq->notes = $newText;

                if ($this->argument('ccr')) {
                    $pq->currency_current_rate = $this->argument('ccr');
                }

                $pq->updated_at = now();
                $pq->save();
                $this->sendPaymentReqApproveEmail($pq);
            }
        }
        return 0;
    }
}
