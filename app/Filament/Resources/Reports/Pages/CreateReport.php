<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Filament\Resources\Reports\ReportResource;
use App\Filament\Resources\Reports\Schemas\ReportForm;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateReport extends CreateRecord
{
    protected static string $resource = ReportResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate the report file
        $filePath = ReportForm::generateReport(
            $data['type'],
            $data['start_date'],
            $data['end_date'],
            Auth::id()
        );

        // Update the data with the generated file path
        $data['file_path'] = $filePath;
        $data['created_by'] = Auth::id();

        return $data;
    }
}
