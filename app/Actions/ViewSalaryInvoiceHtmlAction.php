<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

class ViewSalaryInvoiceHtmlAction extends AbstractAction
{
    public function getTitle()
    {
        return 'View HTML';
    }

    public function getIcon()
    {
        return 'voyager-world';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-info',
            'target' => '_blank',
        ];
    }

    public function getDefaultRoute()
    {
        // Check if invoice_path exists
        if (empty($this->data->invoice_path)) {
            return '#';
        }

        // Generate route with invoice_path as query parameter
        return route('salary-invoice.html', ['path' => $this->data->invoice_path]);
    }

    public function shouldActionDisplayOnDataType()
    {
        // Only show on salary-invoice-logs table
        return $this->dataType->slug == 'salary-invoice-logs' && !empty($this->data->invoice_path);
    }
}
