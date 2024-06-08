<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StudentFee;
use App\Models\User;
use Carbon\Carbon;

class AddStudentFeesForCurrentMonth extends Command
{
    protected $signature = 'add:fees';
    protected $description = 'Add student fees for the current month';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        // Get the current month and year
        $currentMonth = Carbon::now()->format('F');
        $currentYear = Carbon::now()->year;

        // Fetch student id from admin panel setting academy menu
        $students = User::where('role_id', setting('academy.student_role_id'))->where('no_fee', false)->get();

        foreach ($students as $student) {
            // Replace 'your_batch_name' with the appropriate batch name
            $batchName = setting('academy.student_batch_name');

            // Check if a fee record for the current month and year already exists
            $existingFee = StudentFee::where('student_id', $student->id)
                ->whereMonth('date', '=', Carbon::now()->month)
                ->whereYear('date', '=', $currentYear)
                ->first();

            if (!$existingFee) {
                // Create a new StudentFee record for the current student
                StudentFee::create([
                    'batch' => $batchName,
                    'date' => Carbon::now(),
                    'student_id' => $student->id,
                    'notes' => setting('academy.student_fee_note'),
                    'amount' => setting('academy.student_fee_amount'),
                    'status' => 'pending',
                    // You can set other fields as needed
                ]);
            } else {
                // Update the existing fee record
//                $existingFee->update([
//                    'amount' => 2000,
//                    'status' => 'pending',
//                    // You can set other fields as needed
//                ]);
            }
        }

        $this->info("Fees updated for all students for the current month.");
    }
}
