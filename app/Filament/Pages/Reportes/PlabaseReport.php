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
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Carbon\Carbon;

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
                    Select::make('anio')
                        ->label('Año')
                        ->options(array_combine(
                            range(date('Y') - 5, date('Y') + 1),
                            range(date('Y') - 5, date('Y') + 1)
                        ))
                        ->default(date('Y'))
                        ->required(),
                ])
                ->action(function (array $data): void {
                    // Aqui iria la logica para generar el reporte
                    $mes = $data['mes'];
                    $anio = $data['anio'];
                    
                    // Generar el reporte Excel
                    $this->generarReportePlabase($mes, $anio);
                }),
        ];
    }

    private function generarReportePlabase($mes, $anio)
    {
        try {
            // Obtener todos los pacientes de dialisis (que tengan numero de alta)
            $pacientes = Paciente::whereNotNull('nroalta')
                ->orderBy('apellido')
                ->orderBy('nombre')
                ->get();

            // Crear nuevo spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle("PLABASE {$mes}-{$anio}");

            // Configurar encabezados
            $this->configurarEncabezados($sheet, $mes, $anio, $pacientes);

            // Definir estructura de campos segun el CSV
            $campos = $this->obtenerEstructuraCampos();

            $fila = 2; // Comenzamos en la fila 2 despues de los nombres

            foreach ($campos as $seccion => $camposSeccion) {
                if ($seccion === 'separador_trimestral') {
                    // Fila separadora para analisis trimestral
                    $sheet->setCellValue("A{$fila}", '----- Trimestral -----');
                    $fila++;
                    continue;
                } elseif ($seccion === 'separador_semestral') {
                    // Fila separadora para analisis semestral
                    $sheet->setCellValue("A{$fila}", '----- Semestral -----');
                    $fila++;
                    continue;
                }

                foreach ($camposSeccion as $campo => $etiqueta) {
                    // Escribir etiqueta del campo en la primera columna
                    $sheet->setCellValue("A{$fila}", $etiqueta);

                    // Escribir datos de cada paciente
                    $columna = 2;
                    foreach ($pacientes as $paciente) {
                        // Obtener analisis segun la seccion
                        $analisisMensual = null;
                        $analisisTrimestral = null;
                        $analisisSemestral = null;

                        if ($seccion === 'mensual') {
                            $analisisMensual = $this->obtenerAnalisisMensual($paciente, $mes, $anio);
                        } elseif ($seccion === 'trimestral') {
                            $analisisTrimestral = $this->obtenerUltimoAnalisisTrimestral($paciente, $mes, $anio);
                        } elseif ($seccion === 'semestral') {
                            $analisisSemestral = $this->obtenerUltimoAnalisisSemestral($paciente, $mes, $anio);
                        }

                        // Escribir valor del campo
                        $valor = $this->obtenerValorCampo($campo, $paciente, $analisisMensual, $analisisTrimestral, $analisisSemestral);
                        
                        // Limpiar y validar UTF-8
                        $valor = $this->limpiarUTF8($valor);
                        
                        $columnLetter = Coordinate::stringFromColumnIndex($columna);
                        $sheet->setCellValue("{$columnLetter}{$fila}", $valor);
                        $columna++;
                    }
                    $fila++;
                }
            }

            // Aplicar estilos y formato
            $this->aplicarEstilos($sheet, $fila - 1, count($pacientes));

            // Generar archivo y descargar
            $nombreArchivo = "PLABASE_{$mes}_{$anio}_" . date('YmdHis') . ".xlsx";
            $rutaArchivo = storage_path("app/public/{$nombreArchivo}");

            $writer = new Xlsx($spreadsheet);
            $writer->save($rutaArchivo);

            // Notificación de éxito y descarga
            Notification::make()
                ->title('Reporte PLABASE generado exitosamente')
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

    private function configurarEncabezados($sheet, $mes, $anio, $pacientes)
    {
        // Primera fila: Nombre (etiqueta) + nombres de pacientes
        $sheet->setCellValue('A1', 'Nombre');
        
        $columna = 2; // Comenzamos en la columna B
        foreach ($pacientes as $paciente) {
            // Formato: "N°) APELLIDO INICIALES"
            $numeroOrden = $columna - 1;
            $apellido = $this->limpiarUTF8(strtoupper($paciente->apellido));
            $iniciales = $this->limpiarUTF8(strtoupper(substr($paciente->nombre, 0, 2) . '.'));
            $nombreFormateado = "{$numeroOrden}) {$apellido} {$iniciales}";
            
            $columnLetter = Coordinate::stringFromColumnIndex($columna);
            $sheet->setCellValue("{$columnLetter}1", $nombreFormateado);
            $columna++;
        }
    }

    private function obtenerAnalisisMensual($paciente, $mes, $anio)
    {
        return AnalisisMensual::where('id_paciente', $paciente->id)
            ->whereMonth('fechaanalisis', $mes)
            ->whereYear('fechaanalisis', $anio)
            ->orderBy('fechaanalisis', 'desc')
            ->first();
    }

    private function obtenerUltimoAnalisisTrimestral($paciente, $mes, $anio)
    {
        // Obtener el ultimo analisis trimestral registrado hasta el mes seleccionado
        $fechaLimite = Carbon::createFromDate($anio, $mes, 1)->endOfMonth();
        
        return AnalisisTrimestral::where('id_paciente', $paciente->id)
            ->where('fechaanalisis', '<=', $fechaLimite)
            ->orderBy('fechaanalisis', 'desc')
            ->first();
    }

    private function obtenerUltimoAnalisisSemestral($paciente, $mes, $anio)
    {
        // Obtener el ultimo analisis semestral registrado hasta el mes seleccionado
        $fechaLimite = Carbon::createFromDate($anio, $mes, 1)->endOfMonth();
        
        return AnalisisSemestral::where('id_paciente', $paciente->id)
            ->where('fechaanalisis', '<=', $fechaLimite)
            ->orderBy('fechaanalisis', 'desc')
            ->first();
    }

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
            'separador_trimestral' => [],
            'trimestral' => [
                'fecha_an_trimestral' => 'Fecha An. Trimestral',
                'protocolo_trimestral' => 'No Protocolo trim.',
                'albumina' => 'Albumina',
                'colesterol' => 'Colesterol',
                'trigliseridos' => 'Trigliseridos'
            ],
            'separador_semestral' => [],
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

    private function obtenerValorCampo($campo, $paciente, $analisisMensual, $analisisTrimestral, $analisisSemestral)
    {
        // Campos del paciente
        switch ($campo) {
            case 'nroalta':
                return $paciente->nroalta ?? '';
            case 'apellido':
                return $paciente->apellido ?? '';
            case 'nombre':
                return $paciente->nombre ?? '';
            case 'dni':
                return $paciente->dni ?? '';
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
                case 'urea_creat':
                    return $this->formatearNumero($analisisMensual->urea_creatinina);
                case 'uremia_pos':
                    return $this->formatearNumero($analisisMensual->uremia_post);
                case 'rpu':
                    return $this->formatearNumero($analisisMensual->rpu);
                case 'ktv_daug':
                    return $this->formatearNumero($analisisMensual->ktv_daugiras);
                case 'ktv_basile':
                    return $this->formatearNumero($analisisMensual->ktv_basile);
                case 'tac_urea':
                    return $this->formatearNumero($analisisMensual->tac_urea);
                case 'pcr':
                    return $this->formatearNumero($analisisMensual->pcr);
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
                case 'epo_mensual':
                case 'epo_mensual_kg':
                case 'prom_peso_pre':
                case 'prom_peso_pos':
                case 'prod_k_p':
                    return 'N/D'; // Campos que no tenemos en el modelo actual
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

    private function aplicarEstilos($sheet, $ultimaFila, $numPacientes)
    {
        // Estilo para la primera fila (nombres de pacientes)
        $ultimaColumna = chr(65 + $numPacientes); // A=65, B=66, etc.
        $sheet->getStyle("A1:{$ultimaColumna}1")->applyFromArray([
            'font' => ['bold' => true, 'size' => 10],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E3F2FD']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);

        // Estilo para las etiquetas de los campos (columna A)
        $sheet->getStyle("A2:A{$ultimaFila}")->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F5F5F5']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);

        // Estilo para los datos
        $sheet->getStyle("B2:{$ultimaColumna}{$ultimaFila}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Estilo especial para filas separadoras
        for ($fila = 2; $fila <= $ultimaFila; $fila++) {
            $valorCelda = $sheet->getCell("A{$fila}")->getValue();
            if (strpos($valorCelda, '-----') !== false) {
                $sheet->getStyle("A{$fila}:{$ultimaColumna}{$fila}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2E7D32']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);
            }
        }

        // Ajustar ancho de columnas
        $sheet->getColumnDimension('A')->setWidth(25); // Columna de etiquetas más ancha
        for ($col = 2; $col <= $numPacientes + 1; $col++) {
            $sheet->getColumnDimensionByColumn($col)->setWidth(12);
        }
    }

    /**
     * Formatear números para evitar problemas de codificación
     */
    private function formatearNumero($numero)
    {
        if ($numero === null || $numero === '') {
            return '';
        }

        // Convertir a string y limpiar
        $numero = (string) $numero;
        
        // Reemplazar coma decimal por punto
        $numero = str_replace(',', '.', $numero);
        
        // Validar que sea un número válido
        if (!is_numeric($numero)) {
            return '';
        }

        // Formatear como número con máximo 2 decimales
        return number_format((float) $numero, 2, '.', '');
    }

    /**
     * Limpiar y validar cadenas UTF-8 para evitar problemas de codificación
     */
    private function limpiarUTF8($valor)
    {
        if ($valor === null || $valor === '') {
            return '';
        }

        // Convertir a string si no lo es
        $valor = (string) $valor;

        // Remover caracteres de control y limpiar UTF-8
        $valor = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $valor);
        
        // Verificar y limpiar UTF-8
        if (!mb_check_encoding($valor, 'UTF-8')) {
            $valor = mb_convert_encoding($valor, 'UTF-8', 'UTF-8');
        }

        // Reemplazar caracteres problemáticos comunes
        $replacements = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
            'ñ' => 'n', 'Ñ' => 'N',
            'ü' => 'u', 'Ü' => 'U',
            '°' => 'o'
        ];

        $valor = strtr($valor, $replacements);

        // Asegurar que solo contenga caracteres ASCII seguros
        $valor = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $valor);

        return trim($valor);
    }
}
