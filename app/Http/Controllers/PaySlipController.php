<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaySlipController extends Controller
{
    public function index($PaySlipId)
    {
        $user = Auth::user();
        $PaySlip = \App\Models\Payslip::findOrFail($PaySlipId);
        if ($user->role === 'admin') {
            if ($user->role === 'coach' && $PaySlip->user_id !== $user->id) {
                abort(403, 'Unauthorized action.');
            } else {
                return view('filament.pages.payslip-print', compact('PaySlip'));
            }
        }
                abort(403, 'Unauthorized action.');
    }
}
