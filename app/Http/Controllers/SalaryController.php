<?php

namespace App\Http\Controllers;

use App\Services\SalaryCalculationService;
use App\Services\SalaryInvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SalaryController extends Controller
{
    protected $salaryService;
    protected $invoiceService;

    public function __construct(SalaryCalculationService $salaryService, SalaryInvoiceService $invoiceService)
    {
        $this->salaryService = $salaryService;
        $this->invoiceService = $invoiceService;
    }

    /**
     * Get salary summary for a specific user and month
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserSalarySummary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'month' => 'required|date_format:Y-m',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $result = $this->salaryService->calculateMonthlySalary(
            $request->input('user_id'),
            $request->input('month')
        );

        if (isset($result['error'])) {
            return response()->json([
                'success' => false,
                'message' => $result['error'],
                'data' => $result,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    /**
     * Get salary summary for all users for a specific month
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllUsersSalarySummary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'month' => 'required|date_format:Y-m',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $results = $this->salaryService->calculateAllUsersSalary(
            $request->input('month')
        );

        return response()->json([
            'success' => true,
            'count' => count($results),
            'data' => $results,
        ]);
    }

    /**
     * Get salary summary for current month (default)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrentMonthSalaries(Request $request)
    {
        $currentMonth = now()->format('Y-m');
        $results = $this->salaryService->calculateAllUsersSalary($currentMonth);

        return response()->json([
            'success' => true,
            'month' => $currentMonth,
            'count' => count($results),
            'data' => $results,
        ]);
    }

    /**
     * Generate and display salary invoice for a user
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generateInvoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'month' => 'required|date_format:Y-m',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $html = $this->invoiceService->generateInvoiceHtml(
            $request->input('user_id'),
            $request->input('month')
        );

        return response($html)->header('Content-Type', 'text/html');
    }

    /**
     * Get invoice data as JSON
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInvoiceData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'month' => 'required|date_format:Y-m',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $this->invoiceService->generateInvoiceData(
            $request->input('user_id'),
            $request->input('month')
        );

        if (isset($data['error'])) {
            return response()->json([
                'success' => false,
                'message' => $data['error'],
                'data' => $data,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
