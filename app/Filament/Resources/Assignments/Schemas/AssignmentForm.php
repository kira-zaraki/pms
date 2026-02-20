<?php

namespace App\Filament\Resources\Assignments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

use App\Models\Staff;

class AssignmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('task_type')
                    ->label('Task title')
                    ->required(),
                Select::make('priority')
                    ->options(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'])
                    ->default('medium')
                    ->required(),   
                Select::make('room_id')
                    ->relationShip('room', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('staff_id')
                    ->relationShip('staff', 'id')
                    ->getOptionLabelFromRecordUsing(fn (Staff $record) => $record->user->name)
                    ->searchable()
                    ->preload()
                    ->required(),
                DateTimePicker::make('completed_at')
                    ->columnSpanFull(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
