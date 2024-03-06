<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StudentFee;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class GetStudentFeesByMonth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fees:by-month {month} {status?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieve fees of all students for a given month with the specified status.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $validator = Validator::make($this->arguments(), [
            'month' => 'required|integer|between:1,12',
        ]);

        if ($validator->fails()) {
            $this->error('Invalid month. Month should be an integer between 1 and 12.');
            return 1;
        }

        $month = $this->argument('month');
        $status = $this->argument('status');

        // Validate status argument
        if ($status && !in_array($status, ['pending', 'paid'])) {
            $this->error('Invalid status. Status should be "pending" or "paid".');
            return 1;
        }

        // Get the fees for the given month and status with student names
        $query = StudentFee::whereMonth('date', $month)->with('student');
        if ($status) {
            $query->where('status', $status);
        }
        $fees = $query->get();

        // Output the fees
        $statusMessage = $status ? ucfirst($status) : 'All';
        $this->info("$statusMessage Fees for month $month:");
        foreach ($fees as $fee) {
            $this->line("- Student Name: {$fee->student->name}, Batch: {$fee->batch}, Amount: {$fee->amount}, Status: {$fee->status}");
        }

        return 0;
    }
}
