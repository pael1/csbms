<?php

namespace App\Filament\Resources\HoresPowerResource\Pages;

use Filament\Actions;
use Filament\Support\Enums\MaxWidth;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\HoresPowerResource;

class ListHoresPowers extends ListRecords
{
    protected static string $resource = HoresPowerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->modalWidth(MaxWidth::Small),
        ];
    }
}
