<?php

namespace App\Observers;

use App\Models\UserPayment;
use App\Models\UserPaymentLog;
use Illuminate\Support\Facades\Auth;

class UserPaymentObserver
{
    public function created(UserPayment $userPayment): void
    {
        $this->log($userPayment, 'created');
    }

    public function updated(UserPayment $userPayment): void
    {
        $changes = collect($userPayment->getChanges())
            ->except(['updated_at'])
            ->mapWithKeys(fn($new, $field) => [
                $field => ['old' => $userPayment->getOriginal($field), 'new' => $new],
            ])
            ->all();

        if (empty($changes)) {
            return;
        }

        $this->log($userPayment, 'updated', $changes);
    }

    public function deleted(UserPayment $userPayment): void
    {
        // A force-deleted row is gone from user_payments by the time this
        // fires, and user_payment_logs.user_payment_id is a foreign key to
        // it (cascade-deleted with the row anyway) - logging here would
        // violate that constraint for no lasting benefit.
        if ($userPayment->isForceDeleting()) {
            return;
        }

        $this->log($userPayment, 'deleted');
    }

    private function log(UserPayment $userPayment, string $action, array $changes = []): void
    {
        UserPaymentLog::create([
            'user_payment_id' => $userPayment->id,
            'actor_id' => Auth::id(),
            'action' => $action,
            'changes' => $changes ?: null,
        ]);
    }
}
