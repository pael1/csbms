<?php

namespace App\Filament\Resources\ParticularResource\Pages;

use App\Filament\Resources\ParticularResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewParticular extends ViewRecord
{
    protected static string $resource = ParticularResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
