<?php

namespace App\Mail;

use App\Models\SubscriptionTransaction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public SubscriptionTransaction $transaction;
    public User $recipient;
    public ?string $message1;
    public ?string $message2;
    public ?string $message3;

    /**
     * Create a new message instance.
     */
    public function __construct(SubscriptionTransaction $transaction, User $recipient, ?string $message1 = null, ?string $message2 = null, ?string $message3 = null)
    {
        $this->transaction = $transaction;
        $this->recipient = $recipient;
        $this->message1 = $message1;
        $this->message2 = $message2;
        $this->message3 = $message3;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Submitted Successfully',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $member = $this->transaction->subscription->user;

        return new Content(
            view: 'Mailler.payment_submitted',
            with: [
                'full_name' => $this->recipient->name,
                'message1' => $this->message1,
                'message2' => $this->message2,
                'message3' => $this->message3,
                'reference_no' => $this->transaction->reference_no,
                'address' => $member->address,
                'date_submitted' => $this->transaction->created_at->format('F j, Y \a\t g:i A'),
                'proof_img' => asset('storage/' . $this->transaction->proof_of_payment),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}