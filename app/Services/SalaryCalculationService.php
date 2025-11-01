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

        // Get all leaves for the month
        $leaves = Leave::where('user_id', $userId)
            ->where(function ($query) use ($monthStart, $monthEnd) {
                $query->whereBetween('start_date', [$monthStart, $monthEnd])
                    ->orWhereBetween('end_date', [$monthStart, $monthEnd])
                    ->orWhere(function ($q) use ($monthStart, $monthEnd) {
                        $q->where('start_date', '<=', $monthStart)
                            ->where('end_date', '>=', $monthEnd);
                    });
            })
            ->get();

        // Calculate total leave days in this month
        $totalLeaveDays = 0;
        $leaveDetails = [];
        foreach ($leaves as $leave) {
            $leaveStart = Carbon::parse($leave->start_date);
            $leaveEnd = $leave->end_date ? Carbon::parse($leave->end_date) : $leaveStart->copy();

            // Calculate days within the month
            $rangeStart = $leaveStart->greaterThan($monthStart) ? $leaveStart : $monthStart;
            $rangeEnd = $leaveEnd->lessThan($monthEnd) ? $leaveEnd : $monthEnd;

            $daysInMonth = $rangeStart->diffInDays($rangeEnd) + 1;
            $totalLeaveDays += $daysInMonth;

            $leaveDetails[] = [
                'id' => $leave->id,
                'start_date' => $leave->start_date,
                'end_date' => $leave->end_date,
                'reason' => $leave->reason,
                'days_in_month' => $daysInMonth,
            ];
        }

        // Calculate exceeded leave days
        $exceededLeaveDays = max(0, $totalLeaveDays - $allowedMonthlyLeaves);

        // Calculate daily salary
        $dailySalary = round($contract->monthly_salary / 30, 2);

        // Calculate leave deduction
        $leaveDeduction = round($exceededLeaveDays * $dailySalary, 2);

        // Get all fines for the month
        $fines = Fine::where('user_id', $userId)
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->get();

        $totalFines = 0;
        $totalFinesPaid = 0;
        $fineDetails = [];
        foreach ($fines as $fine) {
            $totalFines += $fine->amount;
            $totalFinesPaid += $fine->paid ?? 0;
            $fineDetails[] = [
                'id' => $fine->id,
                'date' => $fine->date,
                'amount' => $fine->amount,
                'paid' => $fine->paid ?? 0,
                'reason' => $fine->reason,
                'note' => $fine->note,
            ];
        }

        $unpaidFines = $totalFines - $totalFinesPaid;

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

        // Calculate net salary (Gross - Fines only)
        $netSalary = round($contract->monthly_salary - $unpaidFines, 2);

        // Calculate total deductions (for reference)
        $totalDeductions = $leaveDeduction + $unpaidFines + $totalAdvance;

        // Calculate final payable amount (Net Salary - Leaves - Advances)
        $finalPayable = round($netSalary - $leaveDeduction - $totalAdvance, 2);

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
                'allowed_monthly_leaves' => $allowedMonthlyLeaves,
                'total_leave_days' => $totalLeaveDays,
                'exceeded_leave_days' => $exceededLeaveDays,
                'leave_deduction' => $leaveDeduction,
                'details' => $leaveDetails,
            ],
            'fines' => [
                'total_fines' => $totalFines,
                'total_paid' => $totalFinesPaid,
                'unpaid_fines' => $unpaidFines,
                'details' => $fineDetails,
            ],
            'advances' => [
                'total_advance' => $totalAdvance,
                'details' => $advanceDetails,
            ],
            'summary' => [
                'gross_salary' => $contract->monthly_salary,
                'net_salary' => $netSalary, // Gross - Fines only
                'total_deductions' => $totalDeductions,
                'final_payable' => $finalPayable, // Net - Leaves - Advances
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
