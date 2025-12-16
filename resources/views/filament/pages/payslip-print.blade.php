<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip - {{ $record->employee->name ?? 'Employee' }}</title>
    <link rel="stylesheet" href="{{ asset('css/payslip.css') }}">
</head>
<body>
    <div class="payslip-container">
        <!-- Company Header -->
        <div class="payslip-header">
            <div class="payslip-company-name">MULTIFLEX GYM MANAGEMENT SYSTEM</div>
            <div class="payslip-company-info">123 Fitness Street, Health City, Philippines 1000</div>
            <div class="payslip-company-info">Tel: (02) 8123-4567 | Email: info@multiflex.com</div>
        </div>

        <!-- Payslip Title -->
        <div class="payslip-title">Payslip</div>

        <!-- Employee Information -->
        <div class="payslip-section">
            <div class="payslip-section-title">Employee Information</div>
            <div class="payslip-info-grid">
                <div class="payslip-info-item">
                    <span class="payslip-info-label">Employee Name:</span>
                    <span class="payslip-info-value">{{ $record->employee->name ?? 'N/A' }}</span>
                </div>
                <div class="payslip-info-item">
                    <span class="payslip-info-label">Email:</span>
                    <span class="payslip-info-value">{{ $record->employee->email ?? 'N/A' }}</span>
                </div>
                <div class="payslip-info-item">
                    <span class="payslip-info-label">Position:</span>
                    <span class="payslip-info-value">Fitness Coach</span>
                </div>
                <div class="payslip-info-item">
                    <span class="payslip-info-label">Pay Period:</span>
                    <span class="payslip-info-value">{{ $record->period_start->format('M j, Y') }} - {{ $record->period_end->format('M j, Y') }}</span>
                </div>
                <div class="payslip-info-item">
                    <span class="payslip-info-label">Days Attended:</span>
                    <span class="payslip-info-value">{{ $record->days_attended ?? 0 }} days</span>
                </div>
            </div>
        </div>

        <!-- Earnings Section -->
        <div class="payslip-section">
            <div class="payslip-section-title">Earnings</div>
            <table class="payslip-earnings-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Basic Salary</td>
                        <td>₱{{ number_format($record->basic_salary ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Monthly Basic Salary</td>
                        <td>₱{{ number_format($record->total_salary ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Allowances</td>
                        <td>₱{{ number_format($record->allowances ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Overtime Pay</td>
                        <td>₱{{ number_format($record->overtime_pay ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td><strong>TOTAL EARNINGS</strong></td>
                        <td><strong>₱{{ number_format(($record->basic_salary ?? 0) + ($record->allowances ?? 0) + ($record->overtime_pay ?? 0), 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Deductions Section -->
        <div class="payslip-section">
            <div class="payslip-section-title">Deductions</div>
            <table class="payslip-deductions-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>SSS</td>
                        <td>₱{{ number_format($record->sss ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>PhilHealth</td>
                        <td>₱{{ number_format($record->philhealth ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>PAG-IBIG</td>
                        <td>₱{{ number_format($record->pagibig ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Withholding Tax</td>
                        <td>₱{{ number_format($record->tax ?? 0, 2) }}</td>
                    </tr>
                    <tr class="payslip-total-row payslip-total-deductions">
                        <td><strong>TOTAL DEDUCTIONS</strong></td>
                        <td><strong>₱{{ number_format($record->total_deductions ?? 0, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Net Pay -->
        <div class="payslip-net-pay">
            Net Pay: ₱{{ number_format($record->net_pay ?? 0, 2) }}
        </div>

        <!-- Footer -->
        <div class="payslip-footer">
            <div>Generated on {{ now()->format('F j, Y g:i A') }}</div>
            <div class="payslip-note">This payslip is generated automatically by the Multiflex Gym Management System.</div>
        </div>
    </div>

    <script>
        // Auto-print functionality (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>