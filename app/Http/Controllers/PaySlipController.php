<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaySlipController extends Controller
{
    public function index($PaySlipId)
    {
        $PaySlip = \App\Models\Payslip::findOrFail($PaySlipId);
        if(Auth::user()->roles()->where('name', 'admin')->exists()){
            abort(403, 'Unauthorized action.');
        }
        return view('filament.pages.payslip-print', compact('PaySlip'));
    }
}
