<?php

namespace App\Services;

use App\Models\SubscriptionTransaction;
use App\Models\User;
use App\Mail\PaymentSubmitted;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Collection;

class PaymentMailService
{
    /**
     * Send notification email when a payment is submitted to all admin users
     * Note: True bulk sending with personalization requires individual emails
     */
    public function sendPaymentSubmittedNotification($member_email, SubscriptionTransaction $transaction, ?string $message1 = null, ?string $message2 = null, ?string $message3 = null): void
    {
        // Get all admin emails
        $adminEmails = User::where('role', 'admin')
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->pluck('email')
            ->toArray();

        if (empty($adminEmails)) {
            return;
        }

        // Get admin users for personalization
        $adminUsers = User::whereIn('email', $adminEmails)->get()->keyBy('email');

        // Set default admin messages if not provided
        $message1 = $message1 ?? "A new payment has been submitted and is now ready for your review. Below are the details provided by the payer:";
        $message2 = $message2 ?? "Please verify the payment at your earliest convenience. If any details appear incomplete or unclear, feel free to follow up with the payer";
        $message3 = $message3 ?? "Thank you!";

        // Send personalized emails to each admin immediately (works without queue worker)
        foreach ($adminEmails as $email) {
            $admin = $adminUsers[$email];
            Mail::to($email)->send(new PaymentSubmitted($transaction, $admin, $message1, $message2, $message3));
        }
        // Send email to member
        $memberUser = User::where('email', $member_email)->first();
        if ($memberUser) {
            Mail::to($member_email)->queue(new PaymentSubmitted($transaction, $memberUser, $message1, $message2, $message3));
        }
    }
}