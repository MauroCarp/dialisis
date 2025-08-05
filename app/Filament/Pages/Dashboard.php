<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Panel Principal';
    protected static ?string $navigationLabel = 'Panel Principal';
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?int $navigationSort = 0;
    
    public function getTitle(): string
    {
        return 'Centro de Hemodiálisis';
    }
    
    public function getHeading(): string
    {
        return 'Panel de Control - Centro de Hemodiálisis';
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            // \App\Filament\Widgets\PacienteBuscadorWidget::class,
        ];
    }
}
