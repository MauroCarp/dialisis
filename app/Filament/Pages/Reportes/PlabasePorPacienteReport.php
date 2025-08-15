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
use App\Models\AnalisisDiario;
use App\Models\MedicacionPaciente;
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
                            Paciente::whereNull('fechaegreso')
                                ->orderBy('apellido')
                                ->orderBy('nombre')
                                ->get()
                                ->mapWithKeys(function ($paciente) {
                                    return [
                                        $paciente->id => "{$paciente->apellido}, {$paciente->nombre}"
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
            $this->escribirDatosPaciente($sheet, $paciente, $analisisMensual, $analisisTrimestral, $analisisSemestral, $mes, $año);

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
        // Ajustar texto en celdas mergeadas
        $sheet->getStyle('A1')->getAlignment()->setWrapText(true)->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        // Ajustar el alto de la fila para mostrar todo el texto
        $sheet->getRowDimension(1)->setRowHeight(50);
        $sheet->mergeCells('A1:B1');

        $sheet->setCellValue('A2', "Mes: {$mes}/{$año} - Generado: " . now()->format('d/m/Y H:i:s'));
        $sheet->mergeCells('A2:B2');

        // Información del paciente
        $sheet->setCellValue('A4', 'DATOS DEL PACIENTE');
        $sheet->mergeCells('A4:B4');
        
        $sheet->setCellValue('A5', 'DNI:');
        $sheet->setCellValue('B5', $paciente->dnicuitcuil);
        
        $sheet->setCellValue('A6', 'Apellido y Nombre:');
        $sheet->setCellValue('B6', "{$paciente->apellido}, {$paciente->nombre}");

        // Encabezados de análisis
        $fila = 10;
        $sheet->setCellValue("A{$fila}", 'ANÁLISIS MENSUAL');
        $sheet->mergeCells("A{$fila}:B{$fila}");
        
        $fila = 41;
        $sheet->setCellValue("A{$fila}", 'ANÁLISIS TRIMESTRAL');
        $sheet->mergeCells("A{$fila}:B{$fila}");
        
        $fila = 49;
        $sheet->setCellValue("A{$fila}", 'ANÁLISIS SEMESTRAL');
        $sheet->mergeCells("A{$fila}:B{$fila}");
    }

    private function escribirDatosPaciente($sheet, $paciente, $analisisMensual, $analisisTrimestral, $analisisSemestral, $mes, $año)
    {
        $fila = 11;
        
        // Obtener la estructura de campos del reporte general
        $campos = $this->obtenerEstructuraCampos();
        
        // Análisis Mensual
        foreach ($campos['mensual'] as $campo => $etiqueta) {
            $valor = $this->obtenerValorCampo($campo, $paciente, $analisisMensual, $analisisTrimestral, $analisisSemestral, $mes, $año);
            
            $sheet->setCellValue("A{$fila}", $etiqueta . ':');
            
            // Aplicar fórmulas donde corresponda
            switch ($campo) {
                case 'urea_creat':
                        $formula = "=ROUND(B" . ($this->encontrarFilaPorCampo($campos['mensual'], 'uremia_pre') + 10) . "/B" . ($this->encontrarFilaPorCampo($campos['mensual'], 'creatinina') + 10) . ",2)";
                        $sheet->setCellValue("B{$fila}", $formula);
                    break;
                case 'rpu':
                        $filaUremaPre = $this->encontrarFilaPorCampo($campos['mensual'], 'uremia_pre') + 10;
                        $filaUremaPost = $this->encontrarFilaPorCampo($campos['mensual'], 'uremia_pos') + 10;
                        $formula = "=ROUND((B{$filaUremaPre}-B{$filaUremaPost})/B{$filaUremaPre}*100,2)";
                        $sheet->setCellValue("B{$fila}", $formula);
                    break;
                case 'ktv_daug':
                        $filaUremaPre = $this->encontrarFilaPorCampo($campos['mensual'], 'uremia_pre') + 10;
                        $filaUremaPost = $this->encontrarFilaPorCampo($campos['mensual'], 'uremia_pos') + 10;
                        $formula = "=ROUND(-LN(B{$filaUremaPost}/B{$filaUremaPre} - 0.008 * 4)+(4 - 3.5 * B{$filaUremaPost}/B{$filaUremaPre})*0,2)";
                        $sheet->setCellValue("B{$fila}", $formula);
                    break;
                case 'ktv_basile':
                        $filaUremaPre = $this->encontrarFilaPorCampo($campos['mensual'], 'uremia_pre') + 10;
                        $filaUremaPost = $this->encontrarFilaPorCampo($campos['mensual'], 'uremia_pos') + 10;
                        $filaPromPesoPre = $this->encontrarFilaPorCampo($campos['mensual'], 'prom_peso_pre') + 10;
                        $filaPromPesoPos = $this->encontrarFilaPorCampo($campos['mensual'], 'prom_peso_pos') + 10;
                        $formula = "=ROUND(-LN(B{$filaUremaPost}/B{$filaUremaPre} - 0.008 * 4)+(4-3.5*B{$filaUremaPost}/B{$filaUremaPre})*(B{$filaPromPesoPre}-B{$filaPromPesoPos})/B{$filaPromPesoPos},2)";
                        $sheet->setCellValue("B{$fila}", $formula);
                    break;
                case 'tac_urea':
                        $filaUremaPre = $this->encontrarFilaPorCampo($campos['mensual'], 'uremia_pre') + 10;
                        $filaUremaPost = $this->encontrarFilaPorCampo($campos['mensual'], 'uremia_pos') + 10;
                        $filaPromPesoPre = $this->encontrarFilaPorCampo($campos['mensual'], 'prom_peso_pre') + 10;
                        $filaPromPesoPos = $this->encontrarFilaPorCampo($campos['mensual'], 'prom_peso_pos') + 10;
                        $formula = "=ROUND(25.8 + 1.15 * (-LN(B{$filaUremaPost}/B{$filaUremaPre} - 0.008 * 4)+(4 - 3.5 * B{$filaUremaPost}/B{$filaUremaPre})*(B{$filaPromPesoPre}-B{$filaPromPesoPos})/B{$filaPromPesoPos})+56.4/(-LN(B{$filaUremaPost}/B{$filaUremaPre} - 0.008 * 4)+(4-3.5*B{$filaUremaPost}/B{$filaUremaPre})*(B{$filaPromPesoPre}-B{$filaPromPesoPos})/B{$filaPromPesoPos}),2)";
                        $sheet->setCellValue("B{$fila}", $formula);
                    break;
                case 'pcr':
                        $filaUremaPre = $this->encontrarFilaPorCampo($campos['mensual'], 'uremia_pre') + 10;
                        $filaUremaPost = $this->encontrarFilaPorCampo($campos['mensual'], 'uremia_pos') + 10;
                        $filaPromPesoPre = $this->encontrarFilaPorCampo($campos['mensual'], 'prom_peso_pre') + 10;
                        $filaPromPesoPos = $this->encontrarFilaPorCampo($campos['mensual'], 'prom_peso_pos') + 10;
                        $formula = "=ROUND((B{$filaUremaPre} / 0.02143) /(25.8 + 1.15 * (-LN(B{$filaUremaPost}/B{$filaUremaPre} - 0.008 * 4)+(4 - 3.5 * B{$filaUremaPost}/B{$filaUremaPre})*(B{$filaPromPesoPre}-B{$filaPromPesoPos})/B{$filaPromPesoPos})+56.4/(-LN(B{$filaUremaPost}/B{$filaUremaPre} - 0.008 * 4)+(4-3.5*B{$filaUremaPost}/B{$filaUremaPre})*(B{$filaPromPesoPre}-B{$filaPromPesoPos})/B{$filaPromPesoPos}))+0.168,2)";

                        $sheet->setCellValue("B{$fila}", $formula);
                    break;
                case 'prod_k_p':
                        $filaFosfatemia = $this->encontrarFilaPorCampo($campos['mensual'], 'fosfatemia') + 10;
                        $filaCalcemia = $this->encontrarFilaPorCampo($campos['mensual'], 'calcemia') + 10;
                        $formula = "=ROUND(B{$filaFosfatemia} * B{$filaCalcemia},2)";
                        $sheet->setCellValue("B{$fila}", $formula);
                    break;
                case 'epo_mensual_kg':
                        $filaEpoMensual = $this->encontrarFilaPorCampo($campos['mensual'], 'epo_mensual') + 10;
                        $filaPromPesoPos = $this->encontrarFilaPorCampo($campos['mensual'], 'prom_peso_pos') + 10;
                        $formula = "=ROUND(B{$filaEpoMensual}/B{$filaPromPesoPos},2)";
                        $sheet->setCellValue("B{$fila}", $formula);
                    break;
                default:
                    $sheet->setCellValue("B{$fila}", $valor);
                    break;
            }
            
            $fila++;
        }
        
        // Análisis Trimestral
        $fila += 2; // Espacio
        $sheet->setCellValue("A{$fila}", 'ANÁLISIS TRIMESTRAL');
        $sheet->mergeCells("A{$fila}:B{$fila}");
        $fila++;
        
        foreach ($campos['trimestral'] as $campo => $etiqueta) {
            $valor = $this->obtenerValorCampo($campo, $paciente, $analisisMensual, $analisisTrimestral, $analisisSemestral, $mes, $año);
            $sheet->setCellValue("A{$fila}", $etiqueta . ':');
            $sheet->setCellValue("B{$fila}", $valor);
            $fila++;
        }
        
        // Análisis Semestral  
        $fila += 2; // Espacio
        $sheet->setCellValue("A{$fila}", 'ANÁLISIS SEMESTRAL');
        $sheet->mergeCells("A{$fila}:B{$fila}");
        $fila++;
        
        foreach ($campos['semestral'] as $campo => $etiqueta) {
            $valor = $this->obtenerValorCampo($campo, $paciente, $analisisMensual, $analisisTrimestral, $analisisSemestral, $mes, $año);
            $sheet->setCellValue("A{$fila}", $etiqueta . ':');
            $sheet->setCellValue("B{$fila}", $valor);
            $fila++;
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

    // Métodos copiados del reporte general para mantener consistencia
    private function obtenerEstructuraCampos()
    {
        return [
            'mensual' => [
                'fecha_an_mensual' => 'Fecha An. Mensual',
                'protocolo_mensual' => 'No Protocolo mens.',
                'hematocrito' => 'Hematocrito',
                'hemoglobina' => 'Hemoglobina',
                'rto_blancos' => 'Rto. blancos',
                'rto_rojos' => 'Rto. rojos',
                'rto_plaquetas' => 'Rto. plaquetas',
                'epo_mensual' => 'EPO Mensual',
                'epo_mensual_kg' => 'EPO Mensual X KG',
                'creatinina' => 'Creatinina',
                'uremia_pre' => 'Uremia Pre',
                'urea_creat' => 'Urea/Creat.',
                'uremia_pos' => 'Uremia Pos',
                'rpu' => 'RPU',
                'ktv_daug' => 'Ktv Daug.',
                'ktv_basile' => 'Ktv Basile',
                'tac_urea' => 'TAC urea',
                'pcr' => 'PCR',
                'prom_peso_pre' => 'Prom. Peso Pre',
                'prom_peso_pos' => 'Prom. Peso Pos',
                'sodio' => 'Sodio',
                'potasio' => 'Potasio',
                'calcemia' => 'Calcemia',
                'fosfatemia' => 'Fosfatemia',
                'prod_k_p' => 'PROD K x P',
                'fosf_alcalina' => 'Fosf. alcalina',
                'gpt' => 'GPT',
                'got' => 'GOT'
            ],
            'trimestral' => [
                'fecha_an_trimestral' => 'Fecha An. Trimestral',
                'protocolo_trimestral' => 'No Protocolo trim.',
                'albumina' => 'Albumina',
                'colesterol' => 'Colesterol',
                'trigliseridos' => 'Trigliseridos'
            ],
            'semestral' => [
                'fecha_an_semestral' => 'Fecha An. Semestral',
                'protocolo_semestral' => 'No Protocolo sem.',
                'hbsag' => 'HbsAg',
                'antihbsag' => 'AntiHbsAg',
                'valor_antihbsag' => 'Valor AntiHbsAg',
                'antihcv' => 'AntiHCV',
                'antihiv' => 'AntiHIV',
                'anticore' => 'AntiCore',
                'pth' => 'PTH',
                'ferritina' => 'Ferritina',
                'ferremia' => 'Ferremia'
            ]
        ];
    }

    private function obtenerValorCampo($campo, $paciente, $analisisMensual, $analisisTrimestral, $analisisSemestral, $mes, $anio)
    {
        // Campos del paciente
        switch ($campo) {
            case 'apellido':
                return $paciente->apellido ?? '';
            case 'nombre':
                return $paciente->nombre ?? '';
            case 'dni':
                return $paciente->dnicuitcuil ?? '';
        }

        // Campos de analisis mensual
        if ($analisisMensual) {
            switch ($campo) {
                case 'fecha_an_mensual':
                    return $analisisMensual->fechaanalisis ? $analisisMensual->fechaanalisis->format('d/m/Y') : 'N/D';
                case 'protocolo_mensual':
                    return $analisisMensual->protocolo ?? 'N/D';
                case 'hematocrito':
                    return $this->formatearNumero($analisisMensual->hematocrito);
                case 'hemoglobina':
                    return $this->formatearNumero($analisisMensual->hemoglobina);
                case 'rto_blancos':
                    return $this->formatearNumero($analisisMensual->rto_blancos);
                case 'rto_rojos':
                    return $this->formatearNumero($analisisMensual->rto_rojos);
                case 'rto_plaquetas':
                    return $this->formatearNumero($analisisMensual->rto_plaquetas);
                case 'creatinina':
                    return $this->formatearNumero($analisisMensual->creatinina);
                case 'uremia_pre':
                    return $this->formatearNumero($analisisMensual->uremia_pre);
                case 'uremia_pos':
                    return $this->formatearNumero($analisisMensual->uremia_post);
                case 'sodio':
                    return $this->formatearNumero($analisisMensual->sodio);
                case 'potasio':
                    return $this->formatearNumero($analisisMensual->potasio);
                case 'calcemia':
                    return $this->formatearNumero($analisisMensual->calcemia);
                case 'fosfatemia':
                    return $this->formatearNumero($analisisMensual->fosfatemia);
                case 'fosf_alcalina':
                    return $this->formatearNumero($analisisMensual->fosfatasa_alcalina);
                case 'gpt':
                    return $this->formatearNumero($analisisMensual->gpt);
                case 'got':
                    return $this->formatearNumero($analisisMensual->got);
                case 'prom_peso_pre':
                    return $this->calcularPromedioPeso($paciente->id, $mes, $anio, 'pesopre');
                case 'prom_peso_pos':
                    return $this->calcularPromedioPeso($paciente->id, $mes, $anio, 'pesopost');
                case 'epo_mensual':
                    // Obtener la suma de medicación EPO (id_medicacion = 2) para el mes/año/paciente
                    try {
                        $totalEpo = \App\Models\MedicacionPaciente::where('id_paciente', $paciente->id)
                            ->where('id_medicacion', 2)
                            ->whereYear('fechamedicacion', $anio)
                            ->whereMonth('fechamedicacion', $mes)
                            ->whereNotNull('cantidad')
                            ->where('cantidad', '>', 0)
                            ->sum('cantidad');
                        return $this->formatearNumero($totalEpo);
                    } catch (\Exception $e) {
                        return 'N/D';
                    }
                case 'tac_urea':
                case 'rpu':
                case 'ktv_daug':
                case 'ktv_basile':
                case 'pcr':
                case 'epo_mensual_kg':
                case 'prod_k_p':
                case 'urea_creat':
                    return ''; // Estos se calcularán con fórmulas
            }
        }

        // Campos de analisis trimestral
        if ($analisisTrimestral) {
            switch ($campo) {
                case 'fecha_an_trimestral':
                    return $analisisTrimestral->fechaanalisis ? $analisisTrimestral->fechaanalisis->format('d/m/Y') : 'N/D';
                case 'protocolo_trimestral':
                    return $analisisTrimestral->protocolo ?? 'N/D';
                case 'albumina':
                    return $this->formatearNumero($analisisTrimestral->albumina);
                case 'colesterol':
                    return $this->formatearNumero($analisisTrimestral->colesterol);
                case 'trigliseridos':
                    return $this->formatearNumero($analisisTrimestral->trigliseridos);
            }
        }

        // Campos de analisis semestral
        if ($analisisSemestral) {
            switch ($campo) {
                case 'fecha_an_semestral':
                    return $analisisSemestral->fechaanalisis ? $analisisSemestral->fechaanalisis->format('d/m/Y') : 'N/D';
                case 'protocolo_semestral':
                    return $analisisSemestral->protocolo ?? 'N/D';
                case 'hbsag':
                    return $analisisSemestral->hbsag ? 'Positivo' : 'Negativo';
                case 'antihbsag':
                    return $analisisSemestral->antihbsag ? 'Positivo' : 'Negativo';
                case 'valor_antihbsag':
                    return $this->formatearNumero($analisisSemestral->valorantihbsag) ?: '0';
                case 'antihcv':
                    return $analisisSemestral->antihcv ? 'Positivo' : 'Negativo';
                case 'antihiv':
                    return $analisisSemestral->antihiv ? 'Positivo' : 'Negativo';
                case 'anticore':
                    return $analisisSemestral->anticore ? 'Positivo' : 'Negativo';
                case 'pth':
                    return $this->formatearNumero($analisisSemestral->pth);
                case 'ferritina':
                    return $this->formatearNumero($analisisSemestral->ferritina);
                case 'ferremia':
                    return $this->formatearNumero($analisisSemestral->ferremia);
            }
        }

        // Si no hay analisis, devolver cadena vacia o N/D segun corresponda
        if (in_array($campo, ['fecha_an_mensual', 'fecha_an_trimestral', 'fecha_an_semestral', 'protocolo_mensual', 'protocolo_trimestral', 'protocolo_semestral'])) {
            return 'N/D';
        }

        return '';
    }

    private function formatearNumero($numero)
    {
        if ($numero === null || $numero === '') {
            return '';
        }
        
        // Convertir a float si es string
        $numero = is_string($numero) ? floatval($numero) : $numero;
        
        // Si es 0, devolver 0
        if ($numero == 0) {
            return '0';
        }
        
        // Formatear con 2 decimales y remover ceros innecesarios
        return rtrim(rtrim(number_format($numero, 2, '.', ''), '0'), '.');
    }

    private function calcularPromedioPeso($pacienteId, $mes, $anio, $tipoPeso)
    {
        try {
            $promedio = \App\Models\AnalisisDiario::where('id_paciente', $pacienteId)
                ->whereYear('fechaanalisis', $anio)
                ->whereMonth('fechaanalisis', $mes)
                ->whereNotNull($tipoPeso)
                ->where($tipoPeso, '>', 0)
                ->avg($tipoPeso);
            
            return $this->formatearNumero($promedio);
        } catch (\Exception $e) {
            return 'N/D';
        }
    }

    private function encontrarFilaPorCampo($campos, $campoBuscado)
    {
        $fila = 0;
        foreach ($campos as $campo => $etiqueta) {
            if ($campo === $campoBuscado) {
                return $fila;
            }
            $fila++;
        }
        return $fila;
    }

    private function aplicarEstilosPorPaciente($sheet)
    {
        // Estilo para títulos principales
        $sheet->getStyle('A1:B1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E3F2FD']]
        ]);

        $sheet->getStyle('A2:B2')->applyFromArray([
            'font' => ['italic' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Estilo para secciones
        foreach ([4, 10, 41, 49] as $fila) {
            $sheet->getStyle("A{$fila}:B{$fila}")->applyFromArray([
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'BBDEFB']]
            ]);
        }

        // Ajustar ancho de columnas
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(20);
    }
}
