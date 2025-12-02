<?php

namespace App\Services;

use App\Models\User;
use App\Mail\ApprovalNotification;
use Illuminate\Support\Facades\Mail;

class UserApprovalMailService
{
    /**
     * Send approval notification email to user
     */
    public function sendApprovalNotification(User $user): void
    {
        if ($user->email) {
            Mail::to($user->email)->send(new ApprovalNotification($user, $user->role));
        }
    }
}