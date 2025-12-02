<?php

namespace App\Services;

use App\Models\Schedule;
use App\Mail\ScheduleNotification;
use Illuminate\Support\Facades\Mail;

class ScheduleMailService
{
    /**
     * Send notification email when a schedule is created
     */
    public function sendScheduleCreatedNotification(Schedule $schedule): void
    {
        $member = $schedule->member;
        $coach = $schedule->coach;

        if ($member && $member->email) {
            Mail::to($member->email)->send(new ScheduleNotification($schedule, 'created'));
        }

        // Optionally notify coach as well
        if ($coach && $coach->email) {
            Mail::to($coach->email)->send(new ScheduleNotification($schedule, 'assigned'));
        }
    }

    /**
     * Send notification email when a schedule is updated
     */
    public function sendScheduleUpdatedNotification(Schedule $schedule): void
    {
        $member = $schedule->member;
        $coach = $schedule->coach;

        if ($member && $member->email) {
            Mail::to($member->email)->send(new ScheduleNotification($schedule, 'updated'));
        }

        if ($coach && $coach->email) {
            Mail::to($coach->email)->send(new ScheduleNotification($schedule, 'updated'));
        }
    }

    /**
     * Send notification email when a schedule status changes
     */
    public function sendScheduleStatusNotification(Schedule $schedule, string $oldStatus): void
    {
        $member = $schedule->member;
        $coach = $schedule->coach;

        if ($member && $member->email) {
            Mail::to($member->email)->send(new ScheduleNotification($schedule, 'status_changed', $oldStatus));
        }

        if ($coach && $coach->email) {
            Mail::to($coach->email)->send(new ScheduleNotification($schedule, 'status_changed', $oldStatus));
        }
    }

    /**
     * Send reminder email before schedule time
     */
    public function sendScheduleReminder(Schedule $schedule): void
    {
        $member = $schedule->member;

        if ($member && $member->email) {
            Mail::to($member->email)->send(new ScheduleNotification($schedule, 'reminder'));
        }
    }
}