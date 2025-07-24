<?php

namespace App\Filament\Resources\MountingTypeResource\Pages;

use Filament\Actions;
use Filament\Support\Enums\MaxWidth;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\MountingTypeResource;

class ListMountingTypes extends ListRecords
{
    protected static string $resource = MountingTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->modalWidth(MaxWidth::Small),
        ];
    }
}
