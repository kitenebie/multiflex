<?php

namespace App\Livewire\Instructor\QRScanner;


use App\Models\AttendanceLog;
use App\Models\User;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;

class Logs extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        $query = AttendanceLog::query();

        if ($lastUserId = Cache::get('last_scanned_user')) {
            $query->where('user_id', $lastUserId);
        } else {
            $query->where('status', null);
        }

        return $table
            ->query($query->orderBy('id', 'desc'))
            ->columns([
                TextColumn::make('user.name')
                    ->label('User'),
                TextColumn::make('time_in')
                    ->dateTime('h:i:s A'),
                TextColumn::make('time_out')
                    ->dateTime('h:i:s A'),
                TextColumn::make('date')
                    ->date('M j, Y'),
            ])
            ->filters([
                // ...
            ])
            ->recordActions([
                // ...
            ])
            ->toolbarActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.instructor.q-r-scanner.logs');
    }
}
