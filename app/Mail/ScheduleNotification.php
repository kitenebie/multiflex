<?php

namespace App\Mail;

use App\Models\Schedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ScheduleNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Schedule $schedule;
    public string $action;
    public ?string $oldStatus;

    /**
     * Create a new message instance.
     */
    public function __construct(Schedule $schedule, string $action, ?string $oldStatus = null)
    {
        $this->schedule = $schedule;
        $this->action = $action;
        $this->oldStatus = $oldStatus;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match($this->action) {
            'created' => 'New Schedule Created',
            'assigned' => 'Schedule Assigned',
            'updated' => 'Schedule Updated',
            'status_changed' => 'Schedule Status Changed',
            'reminder' => 'Schedule Reminder',
            default => 'Schedule Notification'
        };

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'Mailler.schedule_notification',
            with: [
                'schedule' => $this->schedule,
                'action' => $this->action,
                'oldStatus' => $this->oldStatus,
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