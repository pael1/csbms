<?php

namespace App\Filament\Resources\ParticularResource\Pages;

use App\Filament\Resources\ParticularResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditParticular extends EditRecord
{
    protected static string $resource = ParticularResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
