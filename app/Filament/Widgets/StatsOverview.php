<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use App\Models\Unit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total de Unidades', Unit::count())
                ->description('Em todos os empreendimentos')
                ->icon('heroicon-m-building-office-2'),
            
            Stat::make('Unidades Disponíveis', Unit::where('status', 'available')->count())
                ->description('Estoque atual')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Leads Capturados', Lead::count())
                ->description('Clientes interessados')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),
        ];
    }
}