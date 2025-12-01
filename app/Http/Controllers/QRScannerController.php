<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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

        $user = User::where('qr_code', $qrCode)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ]);
        }

        $subscription = Subscription::where('user_id', $user->id)
            ->where('coach_id', Auth::user()->id)
            ->where('status', 'active')
            ->where('end_date', '>=', today())
            ->first();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have a valid active subscription with this coach.',
            ]);
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
                return response()->json([
                    'success' => true,
                    'user' => $user,
                    'attendance_at' => $attendance->time_in,
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


            return response()->json([
                'success' => true,
                'user' => $user,
                'attendance_at' => $attendance->time_in,
                'action' => 'time-in',
            ]);
        }

        // ================================================================
        // TIME OUT
        // ================================================================
        if ($action === 'time-out') {

            if (!$attendance || empty($attendance->time_in)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot Time-Out (No Time-In today)',
                    'user' => $user,
                ]);
            }

            if (!empty($attendance->time_out)) {
                return response()->json([
                    'success' => true,
                    'user' => $user,
                    'attendance_at' => $attendance->time_out,
                    'action' => 'time-out',
                ]);
            }

            $attendance->update([
                'time_out' => now()->toTimeString(),
            ]);

            return response()->json([
                'success' => true,
                'user' => $user,
                'attendance_at' => $attendance->time_out,
                'action' => 'time-out',
            ]);
        }

        return response()->json([
            'success' => true,
            'user' => $user,
            'attendance_at' => $attendance->time_in,
            'action' => 'time-in',
        ]);
    }
}
