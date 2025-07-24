<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Models\Item;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\CustomerResource;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    protected function afterCreate(): void
    {   
        $newRecord = $this->record;
        $data = $this->data;
        foreach ($data['item_num'] as $itemNumber) {
            Item::where('item_number', $itemNumber)->update([
                'customer_id' => $newRecord->id,
            ]);
        }
    }
}
