<?php

namespace App\Filament\Resources\Staff\Schemas;

use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Enums\StaffRole;
use App\Models\Staff;
use App\Services\PmsService;

class StaffForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship(
                        'user', 
                        'name', 
                        modifyQueryUsing: fn (Builder $query, $record) => PmsService::getFreeStaffUsers($query, $record)
                    )
                    ->unique('staff', 'user_id', ignoreRecord: true)
                    ->searchable()
                    ->preload()
                    ->createOptionForm(fn (Schema $schema) => UserForm::configure($schema)->getComponents())
                    ->editOptionForm(fn (Schema $schema) => UserForm::configure($schema)->getComponents())
                    ->required(),
                Select::make('role')
                    ->options(StaffRole::class)
                    ->required(),
                TagsInput::make('specialties')
                    ->columnSpanFull()
                    ->suggestions(Staff::query()->pluck('specialties')->flatten()->unique()->toArray()),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
