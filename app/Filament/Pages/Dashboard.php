<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = '';
    protected static ?string $navigationLabel = 'Inicio';
    
    public function getTitle(): string
    {
        return '';
    }
    
    public function getHeading(): string
    {
        return '';
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\PacienteBuscadorWidget::class,
        ];
    }
}
