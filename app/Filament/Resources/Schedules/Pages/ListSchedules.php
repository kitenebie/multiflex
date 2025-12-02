<?php

namespace App\Filament\Resources\Schedules\Pages;

use App\Filament\Resources\Schedules\ScheduleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Services\ScheduleMailService;

class ListSchedules extends ListRecords
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    protected function afterCreate(): void
    {
        $schedule = $this->record;
        $mailService = new ScheduleMailService();
        $mailService->sendScheduleCreatedNotification($schedule);
    }
    protected function afterSave(): void
    {
        $schedule = $this->record;
        $oldData = $this->oldRecord->toArray();
        $mailService = new ScheduleMailService();

        // Check if status changed
        if ($oldData['status'] !== $schedule->status) {
            $mailService->sendScheduleStatusNotification($schedule, $oldData['status']);
        } else {
            $mailService->sendScheduleUpdatedNotification($schedule);
        }
    }
}
