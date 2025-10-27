<?php

namespace App\Observers;

use App\Models\Fine;
use App\Mail\FineNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class FineObserver
{
    /**
     * Handle the Fine "created" event.
     *
     * @param  \App\Models\Fine  $fine
     * @return void
     */
    public function created(Fine $fine)
    {
        // Send email notification to the user
        try {
            $user = $fine->user;

            if ($user && $user->email) {
                Mail::to($user->email)->send(new FineNotificationMail($fine));

                Log::info("Fine notification email sent to {$user->email} for fine ID: {$fine->id}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to send fine notification email: " . $e->getMessage());
        }
    }

    /**
     * Handle the Fine "updated" event.
     *
     * @param  \App\Models\Fine  $fine
     * @return void
     */
    public function updated(Fine $fine)
    {
        // Optionally send email on updates if needed
        // For now, we only send on creation
    }
}
