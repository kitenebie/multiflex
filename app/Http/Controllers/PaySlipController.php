<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaySlipController extends Controller
{
    public function index($PaySlipId)
    {
        return view('filament.pages.payslip-print');
    }
}
