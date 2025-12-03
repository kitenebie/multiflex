<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Filament\Resources\Reports\ReportResource;
use App\Filament\Resources\Reports\Schemas\ReportForm;
use App\Models\Report;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateReport extends CreateRecord
{
    protected static string $resource = ReportResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set the created_by field
        $data['created_by'] = Auth::id();

        return $data;
    }

    protected function afterCreate(): void
    {
        // Generate the report file after the record is created
        $filePath = ReportForm::generateReport(
            $this->record->type,
            $this->record->start_date,
            $this->record->end_date,
            $this->record->created_by
        );

        // Update the record with the generated file path
        if ($filePath) {
            $this->record->update(['file_path' => $filePath]);
        }
    }
}
