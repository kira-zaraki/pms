<?php

namespace App\Filament\Resources\Staff\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StaffTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->label('User')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('first_name')
                    ->label('First Name')
                    ->getStateUsing(fn($record): string => explode(' ', $record->user->name)[0] ?? '' )
                    ->searchable(),
                TextColumn::make('last_name')
                    ->label('Last Name')
                    ->getStateUsing(fn($record): string => explode(' ', $record->user->name)[1] ?? '' )
                    ->searchable(),
                TextColumn::make('user.email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('user.phone')
                    ->label('Phone')
                    ->searchable(),
                TextColumn::make('role')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
