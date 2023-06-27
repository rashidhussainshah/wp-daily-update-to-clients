<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

class ViewUserPaymentDetailAction extends AbstractAction
{
    public function getTitle()
    {
        return 'View Payment';
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
            'class' => 'btn btn-sm btn-primary pull-right',
        ];
    }

    public function getDefaultRoute()
    {
        return route('voyager.dashboard', ['user_id' => $this->data->id]);
    }
    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug == 'users';
    }
}
