<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

class ViewSalaryInvoicePdfAction extends AbstractAction
{
    public function getTitle()
    {
        return '<i class="voyager-file-text"></i> <span>View PDF</span>';
    }

    public function getIcon()
    {
        return 'voyager-file-text';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-success',
            'target' => '_blank',
            'style' => 'margin-right: 5px;',
        ];
    }

    public function getDefaultRoute()
    {
        // Check if pdf_path exists
        if (empty($this->data->pdf_path)) {
            return '#';
        }

        // Generate route with pdf_path as query parameter
        return route('salary-invoice.pdf', ['path' => $this->data->pdf_path]);
    }

    public function shouldActionDisplayOnDataType()
    {
        // Only show on salary-invoice-logs table
        return $this->dataType->slug == 'salary-invoice-logs' && !empty($this->data->pdf_path);
    }
}
