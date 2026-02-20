<?php

namespace App\Filament\Resources\Rooms\Pages;

use App\Filament\Resources\Rooms\RoomResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRoom extends EditRecord
{
    protected static string $resource = RoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['galleries'] = $this->record->galleries()->pluck('image')->toArray();

        return $data;
    }

    protected function afterSave(): void
    {
        $images = $this->data['galleries'] ?? [];

        $removedGalleries = $this->record->galleries()
        ->whereNotIn('image', $images)
        ->get();

        $removedGalleries->each->delete();

        foreach ($images as $image) {
            $this->record->galleries()->firstOrCreate([
                'image' => $image,
            ]);
        }
    }

}
