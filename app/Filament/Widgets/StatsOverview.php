<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Store;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Usuarios', (string) User::query()->count())
                ->description('Total de usuarios registrados')
                ->icon('heroicon-o-users'),

            Stat::make('Tiendas', (string) Store::query()->count())
                ->description('Total de tiendas registradas')
                ->icon('heroicon-o-building-storefront'),

            Stat::make('Categorías', (string) Category::query()->count())
                ->description('Total de categorías registradas')
                ->icon('heroicon-o-tag'),
        ];
    }
}
