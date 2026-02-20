<?php

namespace App\Filament\Resources\Rooms\Pages;

use App\Filament\Resources\Rooms\RoomResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRoom extends CreateRecord
{
    protected static string $resource = RoomResource::class;

    protected function afterCreate(): void
    {
        $images = $this->data['galleries'] ?? [];

        foreach ($images as $path) {
            $this->record->galleries()->create([
                'image' => $path,
            ]);
        }
    }
}
