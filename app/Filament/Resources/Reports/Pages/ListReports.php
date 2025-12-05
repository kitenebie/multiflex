<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Filament\Resources\Reports\ReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Ymsoft\FilamentTablePresets\Filament\Pages\HasFilamentTablePresets;
use Ymsoft\FilamentTablePresets\Filament\Pages\WithFilamentTablePresets;

class ListReports extends ListRecords  implements HasFilamentTablePresets
{
    use WithFilamentTablePresets;
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
    protected function getTableHeaderActions(): array
    {
        return $this->retrieveVisiblePresetActions();
    }

    protected function handleTableFilterUpdates(): void
    {
        $this->selectedFilamentPreset = null;
    }

    public function updatedTableSort(): void
    {
        $this->selectedFilamentPreset = null;
    }
}
