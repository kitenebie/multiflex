<?php

namespace App\Filament\Resources\Reports\Schemas;

use App\Models\AttendanceLog;
use App\Models\FitnessOffer;
use App\Models\Subscription;
use App\Models\SubscriptionTransaction;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Report Type Selection
                Select::make('type')
                    ->label('Report Type')
                    ->options([
                        'fitness_offers' => 'Fitness Offers',
                        'sales' => 'Sales',
                        'overall_sales' => 'Overall Sales',
                        'attendance' => 'Attendance',
                        'subscription' => 'Subscription',
                        'revenue' => 'Revenue'
                    ])->columnSpanFull()
                    ->required(),

                // Date Range Selection
                DatePicker::make('start_date')
                    ->label('Start Date')
                    ->required(),

                DatePicker::make('end_date')
                    ->label('End Date')
                    ->required()
                    ->after('start_date'),
            ]);
    }

    public static function generateReport(string $type, string $startDate, string $endDate, int $createdBy): string
    {
        $data = [];
        $filename = '';

        switch ($type) {
            case 'fitness_offers':
                $data = self::generateFitnessOffersReport();
                $filename = 'fitness_offers_report_' . now()->format('Y_m_d_H_i_s') . '.xlsx';
                break;

            case 'sales':
                $data = self::generateSalesReport($startDate, $endDate);
                $filename = 'sales_report_' . $startDate . '_to_' . $endDate . '.xlsx';
                break;

            case 'overall_sales':
                $data = self::generateOverallSalesReport($startDate, $endDate);
                $filename = 'overall_sales_report_' . $startDate . '_to_' . $endDate . '.xlsx';
                break;

            case 'attendance':
                $data = self::generateAttendanceReport($startDate, $endDate);
                $filename = 'attendance_report_' . $startDate . '_to_' . $endDate . '.xlsx';
                break;

            case 'subscription':
                $data = self::generateSubscriptionReport($startDate, $endDate);
                $filename = 'subscription_report_' . $startDate . '_to_' . $endDate . '.xlsx';
                break;

            case 'revenue':
                $data = self::generateRevenueReport($startDate, $endDate);
                $filename = 'revenue_report_' . $startDate . '_to_' . $endDate . '.xlsx';
                break;
        }

        // Debug: Log data count
        \Illuminate\Support\Facades\Log::info('Report type: ' . $type . ', Data count: ' . count($data));

        if (!empty($data)) {
            $filePath = 'generated/' . $filename;

            try {
                Excel::store(new class($data) implements FromArray, WithHeadings {
                    protected $data;

                    public function __construct(array $data)
                    {
                        $this->data = $data;
                    }

                    public function array(): array
                    {
                        return $this->data;
                    }

                    public function headings(): array
                    {
                        return array_keys($this->data[0] ?? []);
                    }
                }, $filePath, 'public');

                \Illuminate\Support\Facades\Log::info('Excel file stored successfully: ' . $filePath);
                return $filePath;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Excel storage failed: ' . $e->getMessage());
                return '';
            }
        }

        \Illuminate\Support\Facades\Log::warning('No data generated for report type: ' . $type);
        return '';
    }

    private static function generateFitnessOffersReport(): array
    {
        $fitnessOffers = FitnessOffer::with(['subscriptions', 'upgradeTo'])
            ->withTrashed()
            ->get();

        $data = [];
        foreach ($fitnessOffers as $offer) {
            $data[] = [
                'ID' => $offer->id,
                'Name' => $offer->name,
                'Description' => is_array($offer->description) ? implode(', ', $offer->description) : $offer->description,
                'Price' => $offer->price,
                'Duration (Days)' => $offer->duration_days,
                'Upgrade To' => $offer->upgradeTo?->name ?? 'N/A',
                'Total Subscriptions' => $offer->subscriptions->count(),
                'Created At' => $offer->created_at?->format('Y-m-d H:i:s'),
                'Updated At' => $offer->updated_at?->format('Y-m-d H:i:s'),
                'Deleted At' => $offer->deleted_at?->format('Y-m-d H:i:s') ?? 'Active',
            ];
        }

        return $data;
    }

    private static function generateSalesReport(string $startDate, string $endDate): array
    {
        $transactions = SubscriptionTransaction::with(['subscription.fitnessOffer', 'subscription.user'])
            ->whereBetween('paid_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->withTrashed()
            ->get();

        $data = [];
        foreach ($transactions as $transaction) {
            $data[] = [
                'Transaction ID' => $transaction->id,
                'Subscription ID' => $transaction->subscription_id,
                'Member Name' => $transaction->subscription->user->name ?? 'N/A',
                'Fitness Offer' => $transaction->subscription->fitnessOffer->name ?? 'N/A',
                'Amount' => $transaction->amount,
                'Payment Method' => $transaction->payment_method,
                'Reference No' => $transaction->reference_no,
                'Paid At' => $transaction->paid_at?->format('Y-m-d H:i:s'),
                'Proof of Payment' => $transaction->proof_of_payment ? 'Yes' : 'No',
                'Created At' => $transaction->created_at?->format('Y-m-d H:i:s'),
                'Deleted At' => $transaction->deleted_at?->format('Y-m-d H:i:s') ?? 'Active',
            ];
        }

        return $data;
    }

    private static function generateOverallSalesReport(string $startDate, string $endDate): array
    {
        $transactions = SubscriptionTransaction::with(['subscription.fitnessOffer'])
            ->whereBetween('paid_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->withTrashed()
            ->get();

        $summary = $transactions->groupBy(function ($transaction) {
            return $transaction->subscription->fitnessOffer->name ?? 'Unknown';
        });

        $data = [];
        foreach ($summary as $offerName => $offerTransactions) {
            $totalAmount = $offerTransactions->sum('amount');
            $totalTransactions = $offerTransactions->count();

            $data[] = [
                'Fitness Offer' => $offerName,
                'Total Transactions' => $totalTransactions,
                'Total Amount' => $totalAmount,
                'Average Amount' => $totalTransactions > 0 ? round($totalAmount / $totalTransactions, 2) : 0,
                'Date Range' => $startDate . ' to ' . $endDate,
            ];
        }

        // Add overall summary
        $data[] = [
            'Fitness Offer' => 'OVERALL TOTAL',
            'Total Transactions' => $transactions->count(),
            'Total Amount' => $transactions->sum('amount'),
            'Average Amount' => $transactions->count() > 0 ? round($transactions->sum('amount') / $transactions->count(), 2) : 0,
            'Date Range' => $startDate . ' to ' . $endDate,
        ];

        return $data;
    }

    private static function generateAttendanceReport(string $startDate, string $endDate): array
    {
        $attendanceLogs = AttendanceLog::with('user')
            ->whereBetween('date', [$startDate, $endDate])
            ->withTrashed()
            ->orderBy('date')
            ->orderBy('time_in')
            ->get();

        $data = [];
        foreach ($attendanceLogs as $log) {
            $data[] = [
                'Log ID' => $log->id,
                'Member Name' => $log->user->name ?? 'N/A',
                'Date' => $log->date?->format('Y-m-d'),
                'Time In' => $log->time_in?->format('H:i:s'),
                'Time Out' => $log->time_out?->format('H:i:s'),
                'Status' => $log->status,
                'Created At' => $log->created_at?->format('Y-m-d H:i:s'),
                'Deleted At' => $log->deleted_at?->format('Y-m-d H:i:s') ?? 'Active',
            ];
        }

        return $data;
    }

    private static function generateSubscriptionReport(string $startDate, string $endDate): array
    {
        $subscriptions = Subscription::with(['user', 'fitnessOffer', 'coach', 'subscriptionTransactions'])
            ->whereBetween('start_date', [$startDate, $endDate])
            ->orWhereBetween('end_date', [$startDate, $endDate])
            ->withTrashed()
            ->get();

        $data = [];
        foreach ($subscriptions as $subscription) {
            $totalPaid = $subscription->subscriptionTransactions->sum('amount');

            $data[] = [
                'Subscription ID' => $subscription->id,
                'Member Name' => $subscription->user->name ?? 'N/A',
                'Fitness Offer' => $subscription->fitnessOffer->name ?? 'N/A',
                'Coach Name' => $subscription->coach->name ?? 'N/A',
                'Status' => $subscription->status,
                'Start Date' => $subscription->start_date?->format('Y-m-d'),
                'End Date' => $subscription->end_date?->format('Y-m-d'),
                'Is Extendable' => $subscription->is_extendable ? 'Yes' : 'No',
                'Total Paid' => $totalPaid,
                'Created At' => $subscription->created_at?->format('Y-m-d H:i:s'),
                'Deleted At' => $subscription->deleted_at?->format('Y-m-d H:i:s') ?? 'Active',
            ];
        }

        return $data;
    }

    private static function generateRevenueReport(string $startDate, string $endDate): array
    {
        $transactions = SubscriptionTransaction::with(['subscription.fitnessOffer'])
            ->whereBetween('paid_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->withTrashed()
            ->get();

        $monthlyRevenue = $transactions->groupBy(function ($transaction) {
            return $transaction->paid_at?->format('Y-m');
        });

        $data = [];
        foreach ($monthlyRevenue as $month => $monthTransactions) {
            $monthlyTotal = $monthTransactions->sum('amount');
            $transactionCount = $monthTransactions->count();

            $data[] = [
                'Period' => $month,
                'Total Revenue' => $monthlyTotal,
                'Transaction Count' => $transactionCount,
                'Average Transaction' => $transactionCount > 0 ? round($monthlyTotal / $transactionCount, 2) : 0,
            ];
        }

        // Add overall summary
        $data[] = [
            'Period' => 'TOTAL (' . $startDate . ' to ' . $endDate . ')',
            'Total Revenue' => $transactions->sum('amount'),
            'Transaction Count' => $transactions->count(),
            'Average Transaction' => $transactions->count() > 0 ? round($transactions->sum('amount') / $transactions->count(), 2) : 0,
        ];

        return $data;
    }

    public static function generateExcelReport(array $data, string $filename = 'report.xlsx')
    {
        return Excel::download(new class($data) implements FromArray {
            protected $data;

            public function __construct(array $data)
            {
                $this->data = $data;
            }

            public function array(): array
            {
                return $this->data;
            }
        }, $filename);
    }
}
