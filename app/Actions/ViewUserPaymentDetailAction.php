<?php

namespace App\Actions;

use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Actions\AbstractAction;

class ViewUserPaymentDetailAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Payment';
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
            'class' => 'btn btn-sm btn-primary pull-left',
        ];
    }

    public function getDefaultRoute()
    {
        $user_id = null;
        if ($this->dataType->slug == 'users') {
            $user_id = $this->data->id;
        } elseif ($this->dataType->slug == 'user-payments') {
            $user_id = $this->data->developer_id; // on user-payments, user_id is saved into developer_id column
        }
        return route('voyager.dashboard', ['user_id' => $user_id]);
    }
    public function shouldActionDisplayOnDataType()
    {
        return ($this->dataType->slug == 'users' || $this->dataType->slug == 'user-payments' && (Auth::user()->email == 'rashid.bukhari78600@gmail.com' || Auth::user()->email == 'zaars59208@gmail.com'));
    }
}
