<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

class DownloadSalaryInvoicePdfAction extends AbstractAction
{
    public function getTitle()
    {
        return '<i class="voyager-download"></i> <span>Download PDF</span>';
    }

    public function getIcon()
    {
        return 'voyager-download';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-primary',
            'style' => 'margin-right: 5px;',
        ];
    }

    public function getDefaultRoute()
    {
        // Check if pdf_path exists
        if (empty($this->data->pdf_path)) {
            return '#';
        }

        // Generate route with pdf_path and download=true
        return route('salary-invoice.pdf', ['path' => $this->data->pdf_path, 'download' => 'true']);
    }

    public function shouldActionDisplayOnDataType()
    {
        // Only show on salary-invoice-logs table
        return $this->dataType->slug == 'salary-invoice-logs' && !empty($this->data->pdf_path);
    }
}
