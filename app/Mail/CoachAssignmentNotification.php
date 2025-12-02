<?php

namespace App\Mail;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CoachAssignmentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public User $coach;
    public User $member;
    public Subscription $subscription;

    /**
     * Create a new message instance.
     */
    public function __construct(User $coach, User $member, Subscription $subscription)
    {
        $this->coach = $coach;
        $this->member = $member;
        $this->subscription = $subscription;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Member Assigned',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'Mailler.coach_assignment_notification',
            with: [
                'coach' => $this->coach,
                'member' => $this->member,
                'subscription' => $this->subscription,
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