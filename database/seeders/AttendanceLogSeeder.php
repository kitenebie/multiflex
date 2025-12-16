<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttendanceLog;
use App\Models\User;
use Carbon\Carbon;

class AttendanceLogSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        $startDate = Carbon::now()->subMonths(2)->startOfDay();
        $endDate   = Carbon::now()->endOfDay();

        foreach ($users as $user) {

            $currentDate = $startDate->copy();

            while ($currentDate->lte($endDate)) {

                // Skip weekends
                if ($currentDate->isWeekend()) {
                    $currentDate->addDay();
                    continue;
                }

                // Random time-in between 8:00–9:00 AM
                $timeIn = $currentDate->copy()->setTime(
                    rand(8, 9),
                    rand(0, 59)
                );

                // Random work duration between 7–8 hours
                $workHours   = rand(7, 8);
                $workMinutes = rand(0, 59);

                $timeOut = $timeIn->copy()
                    ->addHours($workHours)
                    ->addMinutes($workMinutes);

                AttendanceLog::create([
                    'user_id'  => $user->id,
                    'date'     => $currentDate->copy(),
                    'time_in'  => $timeIn,
                    'time_out' => $timeOut,
                    'status'   => 'Present',
                ]);

                $currentDate->addDay();
            }
        }
    }
}
