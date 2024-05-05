<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserPayment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetPaymentDetailByUserIdAndStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:detail {user_id} {status}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $userId = $this->argument('user_id');
        $status = $this->argument('status');
        $user = User::find($userId);
        if (!$user) {
            $this->info("User: ({$userId}) not found");
            return 0;
        }
        if ($status != UserPayment::APPROVED_STATUS && $status != UserPayment::REQUESTED_STATUS) {
            $this->info("Current Status: {$status}, should be Approved or Requested ");
            return 0;
        }
        Log::info("Processing started for user {$user->name}");
        $this->info("Processing started for user {$user->name}");

        $upq = UserPayment::whereDeveloperId($userId)
            ->where('status', $status)
            ->get();
        if (!$upq->isEmpty()) {
            $totalPayable = $upq->sum('payable');
            foreach ($upq as $pq) {
                Log::info("ID: ({$pq->id})| payable: {$pq->payable} | Notes: {$pq->notes}");
                $this->info("ID: ({$pq->id})| payable: {$pq->payable} | Notes: {$pq->notes}");
            }
            $this->info("Total Payable: {$totalPayable}");
        } else {
            $this->info("No UserPayments found for user {$user->name} with status {$status}");
        }
        return 0;
    }
}
