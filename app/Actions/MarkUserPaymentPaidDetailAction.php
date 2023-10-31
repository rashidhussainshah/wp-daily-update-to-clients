<?php

namespace App\Actions;

use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Actions\AbstractAction;

class MarkUserPaymentPaidDetailAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Mark paid';
    }

    public function getIcon()
    {
        return 'voyager-eye';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-primary pull-left ml-1',
        ];
    }

    public function getDefaultRoute()
    {
        return route('mark-user-payment-paid', ['id' => $this->data->id]);
    }
    public function shouldActionDisplayOnDataType()
    {
        return ($this->dataType->slug == 'user-payments' && (Auth::user()->email == 'rashid.bukhari78600@gmail.com' || Auth::user()->email == 'zaars59208@gmail.com' || Auth::user()->email == 'Accountant@webpenter.com'));
    }
}
