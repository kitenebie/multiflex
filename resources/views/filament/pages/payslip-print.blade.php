<!doctype html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip</title>
    <script src="/_sdk/element_sdk.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            box-sizing: border-box;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                background: white !important;
            }
        }
    </style>
    <style>
        @view-transition {
            navigation: auto;
        }
    </style>
</head>

<body class="h-full">
    <div class="w-full h-full overflow-auto bg-gradient-to-br from-slate-100 to-slate-200 p-8">
        <div class="max-w-4xl mx-auto bg-white shadow-2xl rounded-lg overflow-hidden"><!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 id="company-name" class="text-3xl font-bold mb-2">Multiflex Fitness Gym</h1>
                        <p id="company-address" class="text-indigo-100">Zone 6, Bulan, Sorsogon</p>
                    </div>
                    <div class="text-right">
                        <h2 class="text-xl font-semibold">PAYSLIP</h2>
                    </div>
                </div>
            </div><!-- Employee Info -->
            <div class="p-8 border-b-2 border-indigo-100">
                <div class="grid grid-cols-2 gap-6">
                    <div><label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Employee
                            Name</label>
                        <p id="employee-name" class="text-lg font-medium text-gray-900 mt-1">
                            {{ strtoupper($PaySlip->employee->name) }}</p>
                    </div>
                    <div><label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Employee ID</label>
                        <p id="employee-id" class="text-lg font-medium text-gray-900 mt-1">{{ $PaySlip->employee->id }}
                        </p>
                    </div>
                    <div><label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Designation</label>
                        <p id="designation" class="text-lg font-medium text-gray-900 mt-1">
                            {{ strtoupper($PaySlip->employee->role) }}</p>
                    </div>
                    <div><label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Pay Period</label>
                        <p id="pay-period" class="text-lg font-medium text-gray-900 mt-1">{{ $PaySlip->period_start->format('M d, Y') }} -
                            {{ $PaySlip->period_end->format('M d, Y') }}</p>
                    </div>
                    <div><label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Payment Date</label>
                        <p id="pay-date" class="text-lg font-medium text-gray-900 mt-1">{{ $PaySlip->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div><!-- Earnings and Deductions -->
            <div class="p-8">
                <div class="grid grid-cols-2 gap-8"><!-- Earnings -->
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-green-500">Earnings</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between"><span class="text-gray-700">Daily Basic Salary</span>
                                <span class="font-semibold text-gray-900">PHP {{ number_format($PaySlip->basic_salary, 2) }}</span>
                            </div>
                            <div class="flex justify-between"><span class="text-gray-700">Monthly Basic Salary</span>
                                <span class="font-semibold text-gray-900">PHP {{ number_format($PaySlip->total_salary, 2) }}</span>
                            </div>
                            <div class="flex justify-between pt-3 border-t-2 border-gray-200"><span
                                    class="font-bold text-gray-900">Total Earnings</span> <span
                                    class="font-bold text-green-600 text-lg">PHP {{ number_format($PaySlip->total_salary, 2) }}</span>
                            </div>
                        </div>
                    </div><!-- Deductions -->
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-red-500">Deductions</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between"><span class="text-gray-700">Income Tax</span> <span
                                    class="font-semibold text-gray-900">PHP {{ number_format($PaySlip->tax, 2) }}</span>
                            </div>
                            <div class="flex justify-between"><span class="text-gray-700">SSS</span> <span
                                    class="font-semibold text-gray-900">PHP {{ number_format($PaySlip->sss, 2) }}</span>
                            </div>
                            <div class="flex justify-between"><span class="text-gray-700">PhilHealth</span> <span
                                    class="font-semibold text-gray-900">PHP {{ number_format($PaySlip->philhealth, 2) }}</span>
                            </div>
                            <div class="flex justify-between"><span class="text-gray-700">Pag-Ibig</span> <span
                                    class="font-semibold text-gray-900">PHP {{ number_format($PaySlip->pagibig, 2) }}</span>
                            </div>
                            <div class="flex justify-between pt-3 border-t-2 border-gray-200"><span
                                    class="font-bold text-gray-900">Total Deductions</span> <span
                                    class="font-bold text-red-600 text-lg">PHP {{ number_format($PaySlip->total_deductions, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- Net Pay -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-8">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-indigo-100 text-sm uppercase tracking-wide mb-1">Net Pay Amount</p>
                        <p class="text-4xl font-bold">PHP {{ number_format($PaySlip->net_pay, 2) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-indigo-100 text-sm">Amount in Words</p>
                        <p class="text-sm font-semibold">{{ numberToWords($PaySlip->net_pay) }}</p>
                    </div>
                </div>
            </div><!-- Footer -->
            <div class="bg-gray-50 p-6 text-center border-t">
                <p class="text-sm text-gray-600">This is a computer-generated payslip and does not require a signature.
                </p>
                <p class="text-xs text-gray-500 mt-2">For queries, please contact HR Department</p>
            </div><!-- Save Button -->
            <div class="p-6 no-print text-center"><button onclick="Save()"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-8 py-3 rounded-lg shadow-lg transition-all duration-200 transform hover:scale-105">
                    Save Payslip </button>
            </div>
        </div>
    </div>
</html>
