<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Actions\AbstractAction;

class ApproveLeaveAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Approve';
    }

    public function getIcon()
    {
        return 'voyager-check';
    }

    public function getPolicy()
    {
        return 'edit';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-success pull-left ml-1',
        ];
    }

    public function getDefaultRoute()
    {
        return route('leaves.approve', ['id' => $this->data->id]);
    }

    public function shouldActionDisplayOnDataType()
    {
        // Only show for the 'leaves' BREAD, only to Ayub, and only when COO approval is required and not yet approved
        if ($this->dataType->slug !== 'leaves') {
            return false;
        }
        if (!Auth::check() || (int) Auth::id() !== (int) User::AYUB_USER_ID) {
            return false;
        }
        // If model doesn't have these attributes for any reason, guard
        $hasCooRequired = isset($this->data->coo_required) ? (bool) $this->data->coo_required : false;
        $hasApprovedAt = isset($this->data->coo_approved_at) && !is_null($this->data->coo_approved_at);
        return $hasCooRequired && !$hasApprovedAt;
    }
}
