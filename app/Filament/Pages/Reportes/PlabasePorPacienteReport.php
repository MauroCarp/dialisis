<?php

namespace App\Filament\Pages\Reportes;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use App\Models\Paciente;

class PlabasePorPacienteReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    
    protected static ?string $navigationLabel = 'PLABASE por Paciente';
    
    protected static ?string $navigationGroup = 'Reportes';
    
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.reportes.plabase-por-paciente-report';
    
    protected static ?string $title = 'Reporte PLABASE por Paciente';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generar')
                ->label('Generar Reporte por Paciente')
                ->icon('heroicon-o-document-arrow-down')
                ->color('primary')
                ->modalHeading('Generar Reporte PLABASE por Paciente')
                ->modalDescription('Seleccione el paciente y el mes para generar el reporte')
                ->modalSubmitActionLabel('Generar')
                ->form([
                    Select::make('paciente_id')
                        ->label('Paciente')
                        ->options(
                            Paciente::whereNotNull('nroalta')
                                ->orderBy('apellido')
                                ->orderBy('nombre')
                                ->get()
                                ->mapWithKeys(function ($paciente) {
                                    return [
                                        $paciente->id => "{$paciente->apellido}, {$paciente->nombre} - Alta: {$paciente->nroalta}"
                                    ];
                                })
                        )
                        ->searchable()
                        ->required(),
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
                    $pacienteId = $data['paciente_id'];
                    $mes = $data['mes'];
                    $año = $data['año'];
                    
                    $paciente = Paciente::find($pacienteId);
                    
                    // Por ahora, solo mostramos una notificación
                    Notification::make()
                        ->title('Reporte PLABASE por Paciente generado')
                        ->body("Reporte generado para {$paciente->nombre} {$paciente->apellido} - {$mes}/{$año}")
                        ->success()
                        ->send();
                        
                    // TODO: Implementar la generación real del reporte
                    // $this->generarReportePlabasePorPaciente($pacienteId, $mes, $año);
                }),
        ];
    }
}
