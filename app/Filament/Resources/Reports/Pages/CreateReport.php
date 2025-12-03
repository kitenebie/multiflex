<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Filament\Resources\Reports\ReportResource;
use App\Filament\Resources\Reports\Schemas\ReportForm;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreateReport extends CreateRecord
{
    protected static string $resource = ReportResource::class;

    protected function afterCreate(): void
    {
        // Debug: Log that afterCreate is called
        Log::info('afterCreate called for report ID: ' . $this->record->id . ', type: ' . $this->record->type);

        try {
            // Generate the report file after the record is created
            $filePath = ReportForm::generateReport(
                $this->record->type,
                $this->record->start_date?->format('Y-m-d'),
                $this->record->end_date?->format('Y-m-d'),
                Auth::id() ?? 1 // Use current user ID or fallback to 1
            );

            // Debug: Log the file path
            Log::info('Generated file path: ' . $filePath);

            // Update the record with the generated file path
            if ($filePath && !empty($filePath)) {
                $this->record->update(['file_path' => $filePath]);
                Log::info('Updated record with file_path: ' . $filePath);
            } else {
                Log::warning('No file path generated for report type: ' . $this->record->type);
            }
        } catch (\Exception $e) {
            Log::error('Error generating report: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }
}
