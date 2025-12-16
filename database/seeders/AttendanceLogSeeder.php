<?php

namespace Database\Seeders;

use App\Models\AttendanceLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AttendanceLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create users if they don't exist
        $users = $this->createUsersIfNeeded();
        
        // Generate attendance logs for the past 30 days
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        foreach ($users as $user) {
            $currentDate = $startDate->copy();
            
            while ($currentDate->lte($endDate)) {
                // Skip weekends (Saturday = 6, Sunday = 0)
                if (!$currentDate->isWeekend()) {
                    $this->createAttendanceLogForUser($user, $currentDate);
                }
                
                $currentDate->addDay();
            }
        }
    }

    /**
     * Create users if they don't exist
     */
    private function createUsersIfNeeded(): \Illuminate\Support\Collection
    {
        $userData = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'role' => 'member',
                'status' => 'active',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'role' => 'member',
                'status' => 'active',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'mike.johnson@example.com',
                'role' => 'coach',
                'status' => 'active',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Sarah Williams',
                'email' => 'sarah.williams@example.com',
                'role' => 'admin',
                'status' => 'active',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'David Brown',
                'email' => 'david.brown@example.com',
                'role' => 'member',
                'status' => 'active',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Lisa Davis',
                'email' => 'lisa.davis@example.com',
                'role' => 'coach',
                'status' => 'active',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Tom Wilson',
                'email' => 'tom.wilson@example.com',
                'role' => 'member',
                'status' => 'active',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Emma Garcia',
                'email' => 'emma.garcia@example.com',
                'role' => 'admin',
                'status' => 'active',
                'password' => bcrypt('password'),
            ],
        ];

        $users = collect();
        
        foreach ($userData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                $data
            );
            $users->push($user);
        }

        return $users;
    }

    /**
     * Create attendance log for a specific user and date
     */
    private function createAttendanceLogForUser(User $user, Carbon $date): void
    {
        // Generate realistic work schedules with 7-8 hours
        $schedules = [
            ['time_in' => '08:00', 'time_out' => '15:30', 'hours' => 7.5], // 7.5 hours
            ['time_in' => '08:30', 'time_out' => '16:00', 'hours' => 7.5], // 7.5 hours
            ['time_in' => '09:00', 'time_out' => '17:00', 'hours' => 8.0], // 8 hours
            ['time_in' => '08:00', 'time_out' => '16:00', 'hours' => 8.0], // 8 hours
            ['time_in' => '09:30', 'time_out' => '17:00', 'hours' => 7.5], // 7.5 hours
            ['time_in' => '08:15', 'time_out' => '16:15', 'hours' => 8.0], // 8 hours
        ];

        // Randomly select a schedule (with some variation)
        $schedule = $schedules[array_rand($schedules)];

        // Add some randomness - 10% chance of being late or leaving early
        $randomFactor = rand(1, 100);
        
        $timeIn = $schedule['time_in'];
        $timeOut = $schedule['time_out'];
        $status = 'present';

        // 5% chance of being late (arrive 15-30 minutes late)
        if ($randomFactor <= 5) {
            $lateMinutes = rand(15, 30);
            $timeIn = Carbon::createFromTimeString($schedule['time_in'])->addMinutes($lateMinutes)->format('H:i');
            $status = 'late';
        }
        // 5% chance of leaving early (leave 15-30 minutes early)
        elseif ($randomFactor <= 10) {
            $earlyMinutes = rand(15, 30);
            $timeOut = Carbon::createFromTimeString($schedule['time_out'])->subMinutes($earlyMinutes)->format('H:i');
            $status = 'left_early';
        }

        // Create the attendance log
        AttendanceLog::create([
            'user_id' => $user->id,
            'date' => $date->format('Y-m-d'),
            'time_in' => $timeIn,
            'time_out' => $timeOut,
            'status' => $status,
        ]);
    }
}