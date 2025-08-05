<?php

namespace App\Filament\Pages\Reportes;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;

class PlabaseReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    
    protected static ?string $navigationLabel = 'PLABASE';
    
    protected static ?string $navigationGroup = 'Reportes';
    
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.reportes.plabase-report';
    
    protected static ?string $title = 'Reporte PLABASE';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generar')
                ->label('Generar Reporte PLABASE')
                ->icon('heroicon-o-document-arrow-down')
                ->color('primary')
                ->modalHeading('Generar Reporte PLABASE')
                ->modalDescription('Seleccione el mes para generar el reporte')
                ->modalSubmitActionLabel('Generar')
                ->form([
                    Select::make('mes')
                        ->label('Mes')
                        ->options([
                            '01' => 'Enero',
                            '02' => 'Febrero',
                            '03' => 'Marzo',
                            '04' => 'Abril',
                            '05' => 'Mayo',
                            '06' => 'Junio',
                            '07' => 'Julio',
                            '08' => 'Agosto',
                            '09' => 'Septiembre',
                            '10' => 'Octubre',
                            '11' => 'Noviembre',
                            '12' => 'Diciembre',
                        ])
                        ->default(date('m'))
                        ->required(),
                    Select::make('año')
                        ->label('Año')
                        ->options(array_combine(
                            range(date('Y') - 5, date('Y') + 1),
                            range(date('Y') - 5, date('Y') + 1)
                        ))
                        ->default(date('Y'))
                        ->required(),
                ])
                ->action(function (array $data): void {
                    // Aquí iría la lógica para generar el reporte
                    $mes = $data['mes'];
                    $año = $data['año'];
                    
                    // Por ahora, solo mostramos una notificación
                    Notification::make()
                        ->title('Reporte PLABASE generado')
                        ->body("Reporte generado para {$mes}/{$año}")
                        ->success()
                        ->send();
                        
                    // TODO: Implementar la generación real del reporte
                    // $this->generarReportePlabase($mes, $año);
                }),
        ];
    }
}
