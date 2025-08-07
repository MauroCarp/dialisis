<?php

namespace App\Filament\Pages\Reportes;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use App\Models\Paciente;
use App\Models\AnalisisMensual;
use App\Models\AnalisisTrimestral;
use App\Models\AnalisisSemestral;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

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
                    
                    // Generar el reporte Excel por paciente
                    $this->generarReportePlabasePorPaciente($pacienteId, $mes, $año);
                }),
        ];
    }

    private function generarReportePlabasePorPaciente($pacienteId, $mes, $año)
    {
        try {
            $paciente = Paciente::findOrFail($pacienteId);

            // Obtener análisis del mes seleccionado
            $analisisMensual = $this->obtenerAnalisisMensual($paciente, $mes, $año);
            $analisisTrimestral = $this->obtenerUltimoAnalisisTrimestral($paciente, $mes, $año);
            $analisisSemestral = $this->obtenerUltimoAnalisisSemestral($paciente, $mes, $año);

            // Crear nuevo spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle("PLABASE {$paciente->apellido}");

            // Configurar encabezados
            $this->configurarEncabezadosPorPaciente($sheet, $paciente, $mes, $año);

            // Escribir datos del paciente
            $this->escribirDatosPaciente($sheet, $paciente, $analisisMensual, $analisisTrimestral, $analisisSemestral);

            // Aplicar estilos
            $this->aplicarEstilosPorPaciente($sheet);

            // Generar archivo y descargar
            $nombreArchivo = "PLABASE_{$paciente->apellido}_{$paciente->nombre}_{$mes}_{$año}_" . date('YmdHis') . ".xlsx";
            $rutaArchivo = storage_path("app/public/{$nombreArchivo}");

            $writer = new Xlsx($spreadsheet);
            $writer->save($rutaArchivo);

            // Notificación de éxito y descarga
            Notification::make()
                ->title('Reporte PLABASE por Paciente generado exitosamente')
                ->body("Archivo: {$nombreArchivo}")
                ->success()
                ->actions([
                    \Filament\Notifications\Actions\Action::make('descargar')
                        ->label('Descargar')
                        ->url(asset("storage/{$nombreArchivo}"))
                        ->openUrlInNewTab(),
                ])
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error al generar el reporte')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    private function configurarEncabezadosPorPaciente($sheet, $paciente, $mes, $año)
    {
        // Título principal
        $sheet->setCellValue('A1', "REPORTE PLABASE - PACIENTE: {$paciente->apellido}, {$paciente->nombre}");
        $sheet->mergeCells('A1:C1');

        $sheet->setCellValue('A2', "Mes: {$mes}/{$año} - Generado: " . now()->format('d/m/Y H:i:s'));
        $sheet->mergeCells('A2:C2');

        // Información del paciente
        $sheet->setCellValue('A4', 'DATOS DEL PACIENTE');
        $sheet->mergeCells('A4:C4');
        
        $sheet->setCellValue('A5', 'N° Alta:');
        $sheet->setCellValue('B5', $paciente->nroalta);
        
        $sheet->setCellValue('A6', 'DNI:');
        $sheet->setCellValue('B6', $paciente->dni);
        
        $sheet->setCellValue('A7', 'Apellido y Nombre:');
        $sheet->setCellValue('B7', "{$paciente->apellido}, {$paciente->nombre}");

        // Encabezados de análisis
        $fila = 10;
        $sheet->setCellValue("A{$fila}", 'ANÁLISIS MENSUAL');
        $sheet->mergeCells("A{$fila}:C{$fila}");
        
        $fila = 25;
        $sheet->setCellValue("A{$fila}", 'ANÁLISIS TRIMESTRAL');
        $sheet->mergeCells("A{$fila}:C{$fila}");
        
        $fila = 35;
        $sheet->setCellValue("A{$fila}", 'ANÁLISIS SEMESTRAL');
        $sheet->mergeCells("A{$fila}:C{$fila}");
    }

    private function escribirDatosPaciente($sheet, $paciente, $analisisMensual, $analisisTrimestral, $analisisSemestral)
    {
        $fila = 11;
        
        // Análisis Mensual
        if ($analisisMensual) {
            $datosMenusales = [
                'Fecha' => $analisisMensual->fechaanalisis ? $analisisMensual->fechaanalisis->format('d/m/Y') : '',
                'Protocolo' => $analisisMensual->protocolo ?? '',
                'Hemoglobina' => $analisisMensual->hemoglobina ?? '',
                'Hematocrito' => $analisisMensual->hematocrito ?? '',
                'Rto. Blancos' => $analisisMensual->rto_blancos ?? '',
                'Rto. Rojos' => $analisisMensual->rto_rojos ?? '',
                'Rto. Plaquetas' => $analisisMensual->rto_plaquetas ?? '',
                'Creatinina' => $analisisMensual->creatinina ?? '',
                'Uremia Pre' => $analisisMensual->uremia_pre ?? '',
                'Uremia Post' => $analisisMensual->uremia_post ?? '',
                'Urea/Creatinina' => $analisisMensual->urea_creatinina ?? '',
                'RPU' => $analisisMensual->rpu ?? '',
                'KT/V Daugiras' => $analisisMensual->ktv_daugiras ?? '',
                'KT/V Basile' => $analisisMensual->ktv_basile ?? '',
                'TAC Urea' => $analisisMensual->tac_urea ?? '',
                'PCR' => $analisisMensual->pcr ?? '',
                'Sodio' => $analisisMensual->sodio ?? '',
                'Potasio' => $analisisMensual->potasio ?? '',
                'Calcemia' => $analisisMensual->calcemia ?? '',
                'Fosfatemia' => $analisisMensual->fosfatemia ?? '',
                'GPT' => $analisisMensual->gpt ?? '',
                'GOT' => $analisisMensual->got ?? '',
                'Fosfatasa Alcalina' => $analisisMensual->fosfatasa_alcalina ?? ''
            ];

            foreach ($datosMenusales as $campo => $valor) {
                $sheet->setCellValue("A{$fila}", $campo . ':');
                $sheet->setCellValue("B{$fila}", $valor);
                $fila++;
            }
        } else {
            $sheet->setCellValue("A{$fila}", 'No hay análisis mensual para este periodo');
        }

        // Análisis Trimestral
        $fila = 26;
        if ($analisisTrimestral) {
            $datosTrimestrales = [
                'Fecha' => $analisisTrimestral->fechaanalisis ? $analisisTrimestral->fechaanalisis->format('d/m/Y') : '',
                'Protocolo' => $analisisTrimestral->protocolo ?? '',
                'Albúmina' => $analisisTrimestral->albumina ?? '',
                'Colesterol' => $analisisTrimestral->colesterol ?? '',
                'Triglicéridos' => $analisisTrimestral->trigliseridos ?? ''
            ];

            foreach ($datosTrimestrales as $campo => $valor) {
                $sheet->setCellValue("A{$fila}", $campo . ':');
                $sheet->setCellValue("B{$fila}", $valor);
                $fila++;
            }
        } else {
            $sheet->setCellValue("A{$fila}", 'No hay análisis trimestral disponible');
        }

        // Análisis Semestral
        $fila = 36;
        if ($analisisSemestral) {
            $datosSemestrales = [
                'Fecha' => $analisisSemestral->fechaanalisis ? $analisisSemestral->fechaanalisis->format('d/m/Y') : '',
                'Protocolo' => $analisisSemestral->protocolo ?? '',
                'HBsAg' => $analisisSemestral->hbsag ? 'Positivo' : 'Negativo',
                'Anti-HBsAg' => $analisisSemestral->antihbsag ? 'Positivo' : 'Negativo',
                'Valor Anti-HBsAg' => $analisisSemestral->valorantihbsag ?? '',
                'Anti-HCV' => $analisisSemestral->antihcv ? 'Positivo' : 'Negativo',
                'Anti-HIV' => $analisisSemestral->antihiv ? 'Positivo' : 'Negativo',
                'Anti-Core' => $analisisSemestral->anticore ? 'Positivo' : 'Negativo',
                'PTH' => $analisisSemestral->pth ?? '',
                'Ferritina' => $analisisSemestral->ferritina ?? '',
                'Ferremia' => $analisisSemestral->ferremia ?? ''
            ];

            foreach ($datosSemestrales as $campo => $valor) {
                $sheet->setCellValue("A{$fila}", $campo . ':');
                $sheet->setCellValue("B{$fila}", $valor);
                $fila++;
            }
        } else {
            $sheet->setCellValue("A{$fila}", 'No hay análisis semestral disponible');
        }
    }

    private function obtenerAnalisisMensual($paciente, $mes, $año)
    {
        return AnalisisMensual::where('id_paciente', $paciente->id)
            ->whereMonth('fechaanalisis', $mes)
            ->whereYear('fechaanalisis', $año)
            ->orderBy('fechaanalisis', 'desc')
            ->first();
    }

    private function obtenerUltimoAnalisisTrimestral($paciente, $mes, $año)
    {
        $fechaLimite = Carbon::createFromDate($año, $mes, 1)->endOfMonth();
        
        return AnalisisTrimestral::where('id_paciente', $paciente->id)
            ->where('fechaanalisis', '<=', $fechaLimite)
            ->orderBy('fechaanalisis', 'desc')
            ->first();
    }

    private function obtenerUltimoAnalisisSemestral($paciente, $mes, $año)
    {
        $fechaLimite = Carbon::createFromDate($año, $mes, 1)->endOfMonth();
        
        return AnalisisSemestral::where('id_paciente', $paciente->id)
            ->where('fechaanalisis', '<=', $fechaLimite)
            ->orderBy('fechaanalisis', 'desc')
            ->first();
    }

    private function aplicarEstilosPorPaciente($sheet)
    {
        // Estilo para títulos principales
        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E3F2FD']]
        ]);

        $sheet->getStyle('A2:C2')->applyFromArray([
            'font' => ['italic' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Estilo para secciones
        foreach ([4, 10, 25, 35] as $fila) {
            $sheet->getStyle("A{$fila}:C{$fila}")->applyFromArray([
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'BBDEFB']]
            ]);
        }

        // Ajustar ancho de columnas
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(15);
    }
}
