<?php

namespace App\Services;

use App\Models\User;
use App\Models\Contract;
use App\Models\Leave;
use App\Models\Fine;
use App\Models\AdvanceSalary;
use Carbon\Carbon;

class SalaryCalculationService
{
    /**
     * Calculate salary summary for a user for a specific month
     *
     * @param int $userId
     * @param string $month Format: 'Y-m' (e.g., '2025-10')
     * @return array
     */
    public function calculateMonthlySalary($userId, $month)
    {
        $user = User::findOrFail($userId);
        $date = Carbon::createFromFormat('Y-m', $month);
        $monthStart = $date->copy()->startOfMonth();
        $monthEnd = $date->copy()->endOfMonth();

        // Get active contract for the user
        $contract = Contract::where('user_id', $userId)
            ->where('status', 'active')
            ->whereNotNull('monthly_salary')
            ->first();

        if (!$contract || !$contract->monthly_salary) {
            return [
                'error' => 'No active contract with monthly salary found for this user',
                'user' => $user,
                'month' => $month,
            ];
        }

        // Get allowed monthly leaves from settings
        $allowedMonthlyLeaves = (int) setting('leaves.max_monthly_leaves', 2);

        // Get all leaves for the month (split by management approval for display vs deduction)
        $allLeavesQuery = Leave::where('user_id', $userId)
            ->where(function ($query) use ($monthStart, $monthEnd) {
                $query->whereBetween('start_date', [$monthStart, $monthEnd])
                    ->orWhereBetween('end_date', [$monthStart, $monthEnd])
                    ->orWhere(function ($q) use ($monthStart, $monthEnd) {
                        $q->where('start_date', '<=', $monthStart)
                            ->where('end_date', '>=', $monthEnd);
                    });
            })
            ->orderBy('start_date');

        $leaves             = $allLeavesQuery->get();
        $deductibleLeaves   = $leaves->where('management_approval', '!=', Leave::MANAGEMENT_APPROVAL_APPROVED);
        $approvedLeaves     = $leaves->where('management_approval', Leave::MANAGEMENT_APPROVAL_APPROVED);

        // Build leave details for display (all leaves including management-approved)
        $leaveDetails = [];
        foreach ($leaves as $leave) {
            $leaveStart  = Carbon::parse($leave->start_date);
            $leaveEnd    = $leave->end_date ? Carbon::parse($leave->end_date) : $leaveStart->copy();
            $rangeStart  = $leaveStart->greaterThan($monthStart) ? $leaveStart : $monthStart;
            $rangeEnd    = $leaveEnd->lessThan($monthEnd) ? $leaveEnd : $monthEnd;
            $daysInMonth = $rangeStart->diffInDays($rangeEnd) + 1;

            $leaveDetails[] = [
                'id'                  => $leave->id,
                'start_date'          => $leave->start_date,
                'end_date'            => $leave->end_date,
                'reason'              => $leave->reason,
                'days_in_month'       => $daysInMonth,
                'management_approval' => $leave->management_approval ?? 'pending',
            ];
        }

        // Only deductible leaves (management_approval != approved) count toward the quota
        $totalLeaveDays = 0;
        $allLeaveDates  = [];

        foreach ($deductibleLeaves as $leave) {
            $leaveStart  = Carbon::parse($leave->start_date);
            $leaveEnd    = $leave->end_date ? Carbon::parse($leave->end_date) : $leaveStart->copy();
            $rangeStart  = $leaveStart->greaterThan($monthStart) ? $leaveStart : $monthStart;
            $rangeEnd    = $leaveEnd->lessThan($monthEnd) ? $leaveEnd : $monthEnd;
            $daysInMonth = $rangeStart->diffInDays($rangeEnd) + 1;
            $totalLeaveDays += $daysInMonth;

            $currentDate = $rangeStart->copy();
            while ($currentDate->lte($rangeEnd)) {
                $allLeaveDates[] = [
                    'date'      => $currentDate->copy(),
                    'is_saturday' => $currentDate->isSaturday(),
                    'leave_id'  => $leave->id,
                    'reason'    => $leave->reason,
                ];
                $currentDate->addDay();
            }
        }

        // Calculate exceeded leave days with Saturday half-day logic
        $exceededLeaveDays = max(0, $totalLeaveDays - $allowedMonthlyLeaves);

        // Calculate deduction value for exceeded days (Saturday = 0.5 day)
        $exceededLeaveDeductionDays = 0;
        $exceededLeaveDetails = [];

        if ($exceededLeaveDays > 0) {
            // Get the last N leave dates (exceeded ones)
            $exceededDates = array_slice($allLeaveDates, -$exceededLeaveDays);

            foreach ($exceededDates as $leaveDate) {
                $deductionValue = $leaveDate['is_saturday'] ? 0.5 : 1;
                $exceededLeaveDeductionDays += $deductionValue;

                $exceededLeaveDetails[] = [
                    'date' => $leaveDate['date']->format('Y-m-d'),
                    'day_name' => $leaveDate['date']->format('l'),
                    'is_saturday' => $leaveDate['is_saturday'],
                    'deduction_days' => $deductionValue,
                    'reason' => $leaveDate['reason'],
                ];
            }
        }

        // Calculate daily salary
        $dailySalary = round($contract->monthly_salary / 30, 2);

        // Calculate leave deduction using the adjusted days (Saturday = 0.5)
        $leaveDeduction = round($exceededLeaveDeductionDays * $dailySalary, 2);

        // Get only DEDUCTED fines for the month
        $deductedFines = Fine::where('user_id', $userId)
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->where('status', 'deducted')
            ->get();

        $finesForDeduction = 0;
        $fineDetails = [];
        foreach ($deductedFines as $fine) {
            $finesForDeduction += $fine->amount;
            $fineDetails[] = [
                'id' => $fine->id,
                'date' => $fine->date,
                'amount' => $fine->amount,
                'reason' => $fine->reason,
                'note' => $fine->note,
                'paid_at' => $fine->paid_at?->format('Y-m-d H:i:s'),
            ];
        }

        // Get advance salaries for the month
        // Note: month field stores complete dates (e.g., 2025-10-15), so we filter by date range
        $advances = AdvanceSalary::where('user_id', $userId)
            ->whereBetween('month', [$monthStart, $monthEnd])
            ->whereIn('status', ['pending', 'approved', 'paid'])
            ->get();

        $totalAdvance = 0;
        $advanceDetails = [];
        foreach ($advances as $advance) {
            $totalAdvance += $advance->amount;
            $advanceDetails[] = [
                'id' => $advance->id,
                'amount' => $advance->amount,
                'reason' => $advance->reason,
                'status' => $advance->status,
                'request_date' => $advance->request_date,
                'approved_date' => $advance->approved_date,
                'deducted_date' => $advance->deducted_date,
                'created_at' => $advance->created_at ? $advance->created_at->format('Y-m-d H:i:s') : null,
            ];
        }

        // Calculate salary after fines (Gross - Fines)
        $salaryAfterFines = round($contract->monthly_salary - $finesForDeduction, 2);

        // Calculate salary after leave deduction (Salary after fines - Leave deduction)
        $salaryAfterLeaves = round($salaryAfterFines - $leaveDeduction, 2);

        // Calculate total deductions (for reference)
        $totalDeductions = $leaveDeduction + $finesForDeduction + $totalAdvance;

        // Calculate net salary (final amount after all deductions including advances)
        $netSalary = round($salaryAfterLeaves - $totalAdvance, 2);

        // Final payable is same as net salary
        $finalPayable = $netSalary;

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'contract' => [
                'id' => $contract->id,
                'title' => $contract->title,
                'monthly_salary' => $contract->monthly_salary,
                'daily_salary' => $dailySalary,
                'currency' => $contract->currency ?? 'PKR',
            ],
            'month' => $month,
            'month_range' => [
                'start' => $monthStart->format('Y-m-d'),
                'end' => $monthEnd->format('Y-m-d'),
            ],
            'leaves' => [
                'allowed_monthly_leaves'        => $allowedMonthlyLeaves,
                'total_leave_days'              => $totalLeaveDays,
                'management_approved_days'      => $approvedLeaves->sum(function ($leave) use ($monthStart, $monthEnd) {
                    $s = Carbon::parse($leave->start_date);
                    $e = $leave->end_date ? Carbon::parse($leave->end_date) : $s->copy();
                    return ($s->greaterThan($monthStart) ? $s : $monthStart)->diffInDays(
                        ($e->lessThan($monthEnd) ? $e : $monthEnd)
                    ) + 1;
                }),
                'exceeded_leave_days'           => $exceededLeaveDays,
                'exceeded_leave_deduction_days' => $exceededLeaveDeductionDays,
                'leave_deduction'               => $leaveDeduction,
                'details'                       => $leaveDetails,
                'exceeded_details'              => $exceededLeaveDetails,
            ],
            'fines' => [
                'fines_for_deduction' => $finesForDeduction, // Only deducted fines
                'details' => $fineDetails,
            ],
            'advances' => [
                'total_advance' => $totalAdvance,
                'details' => $advanceDetails,
            ],
            'summary' => [
                'gross_salary' => $contract->monthly_salary,
                'salary_after_fines' => $salaryAfterFines, // Gross - Fines
                'salary_after_leaves' => $salaryAfterLeaves, // Salary after fines - Leave deduction
                'total_deductions' => $totalDeductions,
                'net_salary' => $netSalary, // Final amount after all deductions
                'final_payable' => $finalPayable,
            ],
        ];
    }

    /**
     * Calculate salary for all users for a specific month
     *
     * @param string $month Format: 'Y-m' (e.g., '2025-10')
     * @return array
     */
    public function calculateAllUsersSalary($month)
    {
        $contracts = Contract::where('status', 'active')
            ->whereNotNull('monthly_salary')
            ->with('user')
            ->get();

        $results = [];
        foreach ($contracts as $contract) {
            $results[] = $this->calculateMonthlySalary($contract->user_id, $month);
        }

        return $results;
    }
}
