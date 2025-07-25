<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Item;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class TotalItemAmount extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Card::make('Sold Item Amount', '₱' . number_format(Item::sum('total_amount'), 2))
                ->description('Total of all items sold')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),
            Card::make('Sales Invoice', Customer::count('id'))
                ->description('Sales Invoice count')
                ->color('success'),
            Card::make('Delivery Receipt', Item::count('id'))
                ->description('Delivery Receipt count')
                ->color('success'),
        ];
    }
}
