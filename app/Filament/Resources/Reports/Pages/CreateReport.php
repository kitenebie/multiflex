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
        Log::info("afterCreate running for report {$this->record->id}");

        try {
            $filePath = ReportForm::generateReport(
                $this->record->type,
                $this->record->start_date?->format('Y-m-d'),
                $this->record->end_date?->format('Y-m-d'),
            );

            Log::info("Generated filePath: '$filePath'");

            if ($filePath && !empty($filePath)) {
                $this->record->file_path = $filePath;
                $this->record->save();

                Log::info("✅ SAVED file_path to database: $filePath");
                Log::info("Record ID: {$this->record->id}, file_path in DB: {$this->record->fresh()->file_path}");
            } else {
                Log::warning("⚠ No file path returned or empty");
            }
        } catch (\Throwable $e) {
            Log::error("❌ afterCreate ERROR: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
        }
    }
}
