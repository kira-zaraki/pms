<?php

namespace App\Filament\Resources\Rooms\Schemas;

use App\Enums\RoomStatus;
use App\Enums\RoomType;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

use App\Jobs\SyncIcalReservations;
use Filament\Schemas\Components\Section;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class RoomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('number')
                    ->required()
                    ->numeric(),
                MarkdownEditor::make('description')
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image()
                    ->columnSpanFull(),
                TextInput::make('capacity')
                    ->required()
                    ->numeric()
                    ->default(1),
                Select::make('status')
                    ->options(RoomStatus::class)
                    ->default('available')
                    ->required(),
                TextInput::make('floor')
                    ->numeric(),
                Select::make('type')
                    ->options(RoomType::class)
                    ->default('normal')
                    ->required(),
                FileUpload::make('galleries')
                    ->label('Room Gallery')
                    ->image()
                    ->multiple()
                    ->directory('rooms/gallery')
                    ->reorderable()
                    ->dehydrated(false)
                    ->panelLayout('grid')
                    ->columnSpanFull(),
                TextInput::make('price_per_night')
                    ->numeric()
                    ->columnSpanFull(),
                Toggle::make('is_cleaned')
                    ->required(),
                Section::make('Channel Manager (iCal Sync)')
                    ->description('Synchronize availability with external OTAs like Airbnb or Booking.com')
                    ->collapsible()
                    ->schema([
                        TextInput::make('ota_ical_import_url')
                            ->label('OTA Import URL')
                            ->placeholder('Paste the iCal link from Airbnb/Booking.com here')
                            ->helperText('Your PMS will pull bookings from this URL.')
                            ->suffixAction(
                                Action::make('sync_now')
                                    ->icon('heroicon-m-arrow-path')
                                    ->tooltip('Sync Now')
                                    ->requiresConfirmation()
                                    ->action(function ($record) {
                                        if (!$record->ota_ical_import_url) {
                                            Notification::make()->title('URL missing')->danger()->send();
                                            return;
                                        }
                                        SyncIcalReservations::dispatch($record);
                                        Notification::make()->title('Syncing started...')->success()->send();
                                    })
                            ),
                        TextInput::make('ical_export_url')
                            ->label('Your Export URL')
                            ->helperText('Copy this link into your Airbnb/Booking.com "Import" settings.')
                            ->formatStateUsing(fn ($record) => $record?->ical_export_token 
                                ? route('ical.export', ['room' => $record->ical_export_token]) 
                                : 'Save the room first to generate a link')
                            ->readOnly()
                            ->dehydrated(false)
                            ->copyable()
                            ->suffixAction(
                                Action::make('copy_url')
                                    ->icon('heroicon-m-clipboard')
                                    ->action(function ($state, $component) {
                                    })
                                    ->extraAttributes([
                                        'x-on:click' => 'window.navigator.clipboard.writeText($state); $tooltip("Copied!", { timeout: 1500 })'
                                    ])
                            ),
                        TextInput::make('last_sync_at')
                            ->label('Last Synchronized')
                            ->placeholder('Never')
                            ->disabled()
                            ->dehydrated(false),
                    ])->columnSpanFull(),
            ]);
    }
}
