<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class SalaryInvoiceController extends Controller
{
    /**
     * View/Download salary invoice PDF
     *
     * @param Request $request
     * @return Response
     */
    public function viewPdf(Request $request)
    {
        $pdfPath = $request->query('path');

        if (!$pdfPath) {
            abort(400, 'PDF path is required');
        }

        // Security: Ensure path doesn't try to access files outside storage
        if (str_contains($pdfPath, '..') || !str_starts_with($pdfPath, 'invoices/')) {
            abort(403, 'Invalid PDF path');
        }

        // Check if file exists in storage
        if (!Storage::exists($pdfPath)) {
            abort(404, 'PDF file not found');
        }

        // Get file contents
        $fileContents = Storage::get($pdfPath);

        // Get filename from path
        $filename = basename($pdfPath);

        // Determine if user wants to download or view
        $disposition = $request->query('download') === 'true' ? 'attachment' : 'inline';

        // Return PDF response
        return response($fileContents, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "{$disposition}; filename=\"{$filename}\"");
    }

    /**
     * View/Download salary invoice HTML
     *
     * @param Request $request
     * @return Response
     */
    public function viewHtml(Request $request)
    {
        $htmlPath = $request->query('path');

        if (!$htmlPath) {
            abort(400, 'HTML path is required');
        }

        // Security: Ensure path doesn't try to access files outside storage
        if (str_contains($htmlPath, '..') || !str_starts_with($htmlPath, 'invoices/')) {
            abort(403, 'Invalid HTML path');
        }

        // Check if file exists in storage
        if (!Storage::exists($htmlPath)) {
            abort(404, 'HTML file not found');
        }

        // Get file contents
        $fileContents = Storage::get($htmlPath);

        // Return HTML response
        return response($fileContents, 200)
            ->header('Content-Type', 'text/html');
    }
}
