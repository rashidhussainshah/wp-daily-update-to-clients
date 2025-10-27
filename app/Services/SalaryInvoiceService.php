<?php

namespace App\Services;

use App\Models\User;
use App\Services\SalaryCalculationService;
use Carbon\Carbon;

class SalaryInvoiceService
{
    protected $salaryService;

    public function __construct(SalaryCalculationService $salaryService)
    {
        $this->salaryService = $salaryService;
    }

    /**
     * Generate salary invoice HTML
     *
     * @param int $userId
     * @param string $month Format: 'Y-m'
     * @return string HTML content
     */
    public function generateInvoiceHtml($userId, $month)
    {
        $data = $this->salaryService->calculateMonthlySalary($userId, $month);

        if (isset($data['error'])) {
            return '<h1>Error: ' . $data['error'] . '</h1>';
        }

        return view('invoices.salary', [
            'data' => $data,
            'companyName' => 'Webpenter',
            'companyAddress' => 'Pakistan',
            'companyPhone' => '',
            'companyEmail' => 'info@webpenter.com',
            'invoiceNumber' => 'SAL-' . strtoupper($data['user']['id']) . '-' . str_replace('-', '', $data['month']),
            'invoiceDate' => Carbon::parse($data['month'] . '-01')->endOfMonth()->format('F d, Y'),
        ])->render();
    }

    /**
     * Generate invoice data for API response
     *
     * @param int $userId
     * @param string $month
     * @return array
     */
    public function generateInvoiceData($userId, $month)
    {
        $data = $this->salaryService->calculateMonthlySalary($userId, $month);

        if (isset($data['error'])) {
            return $data;
        }

        return array_merge($data, [
            'invoice' => [
                'company_name' => 'Webpenter',
                'invoice_number' => 'SAL-' . strtoupper($data['user']['id']) . '-' . str_replace('-', '', $data['month']),
                'invoice_date' => Carbon::parse($data['month'] . '-01')->endOfMonth()->format('F d, Y'),
                'due_date' => Carbon::parse($data['month'] . '-01')->endOfMonth()->addDays(5)->format('F d, Y'),
            ]
        ]);
    }
}
