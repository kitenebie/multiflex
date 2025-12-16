<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaySlipController extends Controller
{
    public function index($PaySlipId)
    {
        $user = Auth::user();
        dd($user);
        $PaySlip = \App\Models\Payslip::findOrFail($PaySlipId);
        if($PaySlip->user_id !== $user->id || $user->role == 'admin'){
            abort(403, 'Unauthorized action.');
        }
        return view('filament.pages.payslip-print', compact('PaySlip'));
    }
}
