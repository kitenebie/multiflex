<?php

namespace App\Http\Controllers;

use App\Events\ExpiredNotification;
use App\Events\scannedNotification;
use App\Models\AttendanceLog;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class QRScannerController extends Controller
{
    public function scan(Request $request)
    {
        $request->validate([
            'qr_code' => 'required',
            'attendance_type' => 'required|in:0,1',
        ]);

        $qrCode = $request->qr_code;
        $attendanceType = $request->attendance_type;
        $action = $attendanceType == '0' ? 'time-in' : 'time-out';

        Log::info('QR Scan initiated', ['qr_code' => $qrCode, 'attendance_type' => $attendanceType, 'coach_id' => Auth::user()->id]);

        $user = User::where('qr_code', $qrCode)->first();

        if (!$user) {
            Log::warning('User not found for QR code', ['qr_code' => $qrCode]);
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ]);
        }
        
        if ($user->roles()->where('name', 'member')->exists()) {
            $subscription = Subscription::where('user_id', $user->id)
                ->where('coach_id', Auth::user()->id)
                ->where('status', 'active')
                ->where('end_date', '>=', today())
                ->first();
            if (!$subscription) {
                Log::warning('No valid subscription', ['user_id' => $user->id, 'coach_id' => Auth::user()->id]);
                $ExSubscription = Subscription::where('user_id', $user->id)
                    ->where('coach_id', Auth::user()->id)->first();
                if ($ExSubscription) {
                    Log::info('Expired subscription event triggered', ['user_id' => $user->id]);
                    event(new ExpiredNotification($user->id, 'Your subscription has expired.'));
                }
                return response()->json([
                    'success' => false,
                    'message' => 'User does not have a valid active subscription with this coach.',
                ]);
            }
        }

        Cache::put('last_scanned_user', $user->id, 60);
        // ---- FIND TODAY'S ATTENDANCE ----
        $attendance = AttendanceLog::where('user_id', $user->id)
            ->where('date', today()->toDateString())
            ->first();

        // ================================================================
        // TIME IN
        // ================================================================
        if ($action === 'time-in') {

            if ($attendance && !empty($attendance->time_in)) {
                Log::info('User already time-in today', ['user_id' => $user->id, 'time_in' => $attendance->time_in]);
                return response()->json([
                    'success' => true,
                    'user' => $user,
                    'attendance_at' => $attendance->time_in?->format('h:i:s A') ?? $attendance->time_in,
                    'action' => 'time-in',
                ]);
            }

            if (!$attendance) {
                $attendance = AttendanceLog::create([
                    'user_id' => $user->id,
                    'date' => today()->toDateString(),
                    'time_in' => now()->toTimeString(),
                    'status' => 'present',
                ]);
            } else {
                $attendance->update([
                    'time_in' => now()->toTimeString(),
                ]);
            }
            Log::info('Time-in recorded', ['user_id' => $user->id, 'attendance_id' => $attendance->id, 'time_in' => $attendance->time_in]);
            event(new scannedNotification($user->id, 'Time-in recorded successfully.'));


            return response()->json([
                'success' => true,
                'user' => $user,
                'attendance_at' => $attendance->time_in?->format('h:i:s A') ?? $attendance->time_in,
                'action' => 'time-in',
            ]);
        }

        // ================================================================
        // TIME OUT
        // ================================================================
        if ($action === 'time-out') {

            if (!$attendance || empty($attendance->time_in)) {
                Log::warning('Attempted time-out without time-in', ['user_id' => $user->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot Time-Out (No Time-In today)',
                    'user' => $user,
                ]);
            }

            if (!empty($attendance->time_out)) {
                Log::info('User already time-out today', ['user_id' => $user->id, 'time_out' => $attendance->time_out]);
                return response()->json([
                    'success' => true,
                    'user' => $user,
                    'attendance_at' => $attendance->time_out?->format('h:i:s A') ?? $attendance->time_out,
                    'action' => 'time-out',
                ]);
            }

            $attendance->update([
                'time_out' => now()->toTimeString(),
            ]);
            Log::info('Time-out recorded', ['user_id' => $user->id, 'attendance_id' => $attendance->id, 'time_out' => $attendance->time_out]);
            event(new scannedNotification($user->id, 'Time-out recorded successfully.'));

            return response()->json([
                'success' => true,
                'user' => $user,
                'attendance_at' => $attendance->time_out?->format('h:i:s A') ?? $attendance->time_out,
                'action' => 'time-out',
            ]);
        }

        return response()->json([
            'success' => true,
            'user' => $user,
            'attendance_at' => $attendance->time_in?->format('h:i:s A') ?? $attendance->time_in,
            'action' => 'time-in',
        ]);
    }
}
