<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Paciente;
use Illuminate\Support\Facades\DB;

class LimpiarDatosCorruptos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datos:limpiar-corruptos {--tabla=pacientes} {--columna=} {--dry-run : Solo mostrar los cambios sin aplicarlos} {--todas-las-columnas : Procesar todas las columnas de texto de la tabla} {--buscar-en-bd : Buscar el patrón TRIAL en toda la base de datos} {--solo-alfabeticos : Limpiar caracteres no alfabéticos de nombres y apellidos}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia los datos corruptos de la migración que contienen el patrón TRIAL y caracteres no alfabéticos en nombres y apellidos. Tablas soportadas: pacientes, pacientesconsultorio, historiasclinicas, historiasclinicasconsultorio';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tabla = $this->option('tabla');
        $columna = $this->option('columna');
        $dryRun = $this->option('dry-run');
        $todasLasColumnas = $this->option('todas-las-columnas');
        $buscarEnBd = $this->option('buscar-en-bd');
        $soloAlfabeticos = $this->option('solo-alfabeticos');

        if ($buscarEnBd) {
            $this->buscarPatronEnBaseDatos();
            return 0;
        }

        if ($soloAlfabeticos) {
            $this->limpiarCaracteresNoAlfabeticos($tabla, $dryRun);
            return 0;
        }

        $this->info("Analizando datos corruptos en la tabla: {$tabla}");

        if ($tabla === 'pacientes') {
            if ($todasLasColumnas) {
                $columnas = ['nombre', 'apellido', 'direccion', 'telefono', 'email'];
                foreach ($columnas as $col) {
                    $this->info("Procesando columna: {$col}");
                    $this->limpiarPacientes($col, $dryRun);
                }
            } elseif ($columna) {
                $this->limpiarPacientes($columna, $dryRun);
            } else {
                $this->error("Debes especificar --columna o usar --todas-las-columnas");
                return 1;
            }
        } elseif ($tabla === 'pacientesconsultorio') {
            if ($todasLasColumnas) {
                $columnas = ['nombre', 'apellido', 'direccion', 'telefono', 'email', 'derivante'];
                foreach ($columnas as $col) {
                    $this->info("Procesando columna: {$col}");
                    $this->limpiarTablaGenerica($tabla, $col, $dryRun);
                }
            } elseif ($columna) {
                $this->limpiarTablaGenerica($tabla, $columna, $dryRun);
            } else {
                $this->error("Debes especificar --columna o usar --todas-las-columnas");
                return 1;
            }
        } elseif ($tabla === 'historiasclinicas') {
            if ($todasLasColumnas) {
                $columnas = ['observaciones'];
                foreach ($columnas as $col) {
                    $this->info("Procesando columna: {$col}");
                    $this->limpiarTablaGenerica($tabla, $col, $dryRun);
                }
            } elseif ($columna) {
                $this->limpiarTablaGenerica($tabla, $columna, $dryRun);
            } else {
                $this->error("Debes especificar --columna o usar --todas-las-columnas");
                return 1;
            }
        } elseif ($tabla === 'historiasclinicasconsultorio') {
            if ($todasLasColumnas) {
                $columnas = ['observaciones'];
                foreach ($columnas as $col) {
                    $this->info("Procesando columna: {$col}");
                    $this->limpiarTablaGenerica($tabla, $col, $dryRun);
                }
            } elseif ($columna) {
                $this->limpiarTablaGenerica($tabla, $columna, $dryRun);
            } else {
                $this->error("Debes especificar --columna o usar --todas-las-columnas");
                return 1;
            }
        } elseif ($tabla === 'pacientesconsultorioobrassociales') {
            if ($todasLasColumnas) {
                $columnas = ['nroafiliado'];
                foreach ($columnas as $col) {
                    $this->info("Procesando columna: {$col}");
                    $this->limpiarTablaGenerica($tabla, $col, $dryRun);
                }
            } elseif ($columna) {
                $this->limpiarTablaGenerica($tabla, $columna, $dryRun);
            } else {
                $this->error("Debes especificar --columna o usar --todas-las-columnas");
                return 1;
            }
        } else {
            $this->error("Tabla no soportada: {$tabla}");
            return 1;
        }

        return 0;
    }

    private function buscarPatronEnBaseDatos()
    {
        $this->info("Buscando el patrón 'TRIAL' en toda la base de datos...");
        
        // Lista de tablas principales que pueden contener datos de texto
        $tablasABuscar = [
            'pacientes' => ['nombre', 'apellido', 'direccion', 'telefono', 'email','dnicuitcuil','gruposanguineo'],
            'pacientesconsultorio' => ['nombre', 'apellido', 'direccion', 'telefono', 'email', 'derivante','dnicuitcuil','gruposanguineo'],
            'historiasclinicas' => ['observaciones'],
            'historiasclinicasconsultorio' => ['observaciones'],
            'provincias' => ['nombre'],
            'localidades' => ['nombre'],
            'obrassociales' => ['nombre'],
            'patologias' => ['nombre'],
            'vacunas' => ['nombre'],
            'medicaciones' => ['nombre'],
            'tiposmedicaciones' => ['nombre'],
        ];

        $encontrados = [];

        foreach ($tablasABuscar as $tabla => $columnas) {
            foreach ($columnas as $columna) {
                try {
                    $count = DB::table($tabla)
                        ->where($columna, 'LIKE', '%TRIAL%')
                        ->count();
                    
                    if ($count > 0) {
                        $encontrados[] = [
                            'tabla' => $tabla,
                            'columna' => $columna,
                            'registros' => $count
                        ];
                        $this->line("✓ Encontrados {$count} registros con TRIAL en {$tabla}.{$columna}");
                    }
                } catch (\Exception $e) {
                    // Tabla o columna no existe, continuar
                    continue;
                }
            }
        }

        if (empty($encontrados)) {
            $this->info("✓ No se encontraron datos corruptos con el patrón TRIAL en la base de datos.");
        } else {
            $this->info("\nResumen de datos corruptos encontrados:");
            foreach ($encontrados as $item) {
                $this->line("- {$item['tabla']}.{$item['columna']}: {$item['registros']} registros");
            }
        }
    }

    private function limpiarTablaGenerica(string $tabla, string $columna, bool $dryRun)
    {
        // Buscar registros con el patrón TRIAL
        $registrosCorruptos = DB::table($tabla)
            ->whereRaw("{$columna} LIKE '%TRIAL%'")
            ->get();
        
        $this->info("Encontrados {$registrosCorruptos->count()} registros con datos corruptos");
        
        if ($registrosCorruptos->isEmpty()) {
            $this->info('No se encontraron datos corruptos.');
            return;
        }

        $actualizados = 0;
        $errores = 0;

        foreach ($registrosCorruptos as $registro) {
            $valorOriginal = $registro->{$columna};
            $valorLimpio = $this->limpiarTexto($valorOriginal);
            
            if ($valorLimpio !== $valorOriginal) {
                if ($dryRun) {
                    $this->line("ID {$registro->id}: '{$valorOriginal}' → '{$valorLimpio}'");
                } else {
                    try {
                        DB::table($tabla)
                            ->where('id', $registro->id)
                            ->update([$columna => $valorLimpio]);
                        $this->line("✓ ID {$registro->id}: '{$valorOriginal}' → '{$valorLimpio}'");
                        $actualizados++;
                    } catch (\Exception $e) {
                        $this->error("✗ Error actualizando ID {$registro->id}: " . $e->getMessage());
                        $errores++;
                    }
                }
            }
        }

        if ($dryRun) {
            $this->info("Modo DRY-RUN: Se mostrarían los cambios pero no se aplicaron.");
            $this->info("Para aplicar los cambios, ejecuta el comando sin --dry-run");
        } else {
            $this->info("Proceso completado:");
            $this->info("- Registros actualizados: {$actualizados}");
            if ($errores > 0) {
                $this->error("- Errores: {$errores}");
            }
        }
    }

    private function limpiarPacientes(string $columna, bool $dryRun)
    {
        // Buscar registros con el patrón TRIAL
        $pacientesCorruptos = Paciente::whereRaw("{$columna} LIKE '%TRIAL%'")->get();
        
        $this->info("Encontrados {$pacientesCorruptos->count()} registros con datos corruptos");
        
        if ($pacientesCorruptos->isEmpty()) {
            $this->info('No se encontraron datos corruptos.');
            return;
        }

        $actualizados = 0;
        $errores = 0;

        foreach ($pacientesCorruptos as $paciente) {
            $valorOriginal = $paciente->{$columna};
            $valorLimpio = $this->limpiarTexto($valorOriginal);
            
            if ($valorLimpio !== $valorOriginal) {
                if ($dryRun) {
                    $this->line("ID {$paciente->id}: '{$valorOriginal}' → '{$valorLimpio}'");
                } else {
                    try {
                        $paciente->{$columna} = $valorLimpio;
                        $paciente->save();
                        $this->line("✓ ID {$paciente->id}: '{$valorOriginal}' → '{$valorLimpio}'");
                        $actualizados++;
                    } catch (\Exception $e) {
                        $this->error("✗ Error actualizando ID {$paciente->id}: " . $e->getMessage());
                        $errores++;
                    }
                }
            }
        }

        if ($dryRun) {
            $this->info("Modo DRY-RUN: Se mostrarían los cambios pero no se aplicaron.");
            $this->info("Para aplicar los cambios, ejecuta el comando sin --dry-run");
        } else {
            $this->info("Proceso completado:");
            $this->info("- Registros actualizados: {$actualizados}");
            if ($errores > 0) {
                $this->error("- Errores: {$errores}");
            }
        }
    }

    private function limpiarCaracteresNoAlfabeticos(string $tabla, bool $dryRun)
    {
        if (!in_array($tabla, ['pacientes', 'pacientesconsultorio'])) {
            $this->error("La opción --solo-alfabeticos solo está disponible para las tablas 'pacientes' y 'pacientesconsultorio'");
            return;
        }

        $this->info("Limpiando caracteres no alfabéticos de nombres y apellidos en la tabla: {$tabla}");

        $columnas = ['nombre', 'apellido'];
        
        foreach ($columnas as $columna) {
            $this->info("\nProcesando columna: {$columna}");
            
            // Buscar registros que contengan números o caracteres especiales en nombres/apellidos
            $registrosConNumeros = DB::table($tabla)
                ->where($columna, 'REGEXP', '[0-9]')
                ->orWhere($columna, 'REGEXP', '[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ ]')
                ->get();
            
            $this->info("Encontrados {$registrosConNumeros->count()} registros con caracteres no alfabéticos en {$columna}");
            
            if ($registrosConNumeros->isEmpty()) {
                $this->info("No se encontraron registros con caracteres no alfabéticos en {$columna}.");
                continue;
            }

            $actualizados = 0;
            $errores = 0;

            foreach ($registrosConNumeros as $registro) {
                $valorOriginal = $registro->{$columna};
                $valorLimpio = $this->limpiarSoloAlfabeticos($valorOriginal);
                
                if ($valorLimpio !== $valorOriginal) {
                    if ($dryRun) {
                        $this->line("ID {$registro->id}: '{$valorOriginal}' → '{$valorLimpio}'");
                    } else {
                        try {
                            DB::table($tabla)
                                ->where('id', $registro->id)
                                ->update([$columna => $valorLimpio]);
                            $this->line("✓ ID {$registro->id}: '{$valorOriginal}' → '{$valorLimpio}'");
                            $actualizados++;
                        } catch (\Exception $e) {
                            $this->error("✗ Error actualizando ID {$registro->id}: " . $e->getMessage());
                            $errores++;
                        }
                    }
                }
            }

            if ($dryRun) {
                $this->info("Modo DRY-RUN para {$columna}: Se mostrarían los cambios pero no se aplicaron.");
            } else {
                $this->info("Proceso completado para {$columna}:");
                $this->info("- Registros actualizados: {$actualizados}");
                if ($errores > 0) {
                    $this->error("- Errores: {$errores}");
                }
            }
        }

        if ($dryRun) {
            $this->info("\nPara aplicar todos los cambios, ejecuta el comando sin --dry-run");
        }
    }

    private function limpiarSoloAlfabeticos(string $texto): string
    {
        if (empty($texto)) {
            return $texto;
        }

        // Eliminar números y caracteres especiales, mantener solo letras, acentos y espacios
        $textoLimpio = preg_replace('/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/', '', $texto);
        
        // Limpiar espacios múltiples y espacios al inicio/final
        $textoLimpio = preg_replace('/\s+/', ' ', $textoLimpio);
        $textoLimpio = trim($textoLimpio);
        
        // Capitalizar correctamente (primera letra de cada palabra en mayúscula)
        $textoLimpio = mb_convert_case($textoLimpio, MB_CASE_TITLE, 'UTF-8');
        
        return $textoLimpio;
    }

    private function limpiarTexto(string $texto): string
    {
        // Patrón: números-TRIAL-texto_original números
        // Ejemplo: "88-TRIAL-RAUL 226" -> "RAUL"
        
        // Patrón regex principal para capturar el texto original
        // \d+-TRIAL-(.+?)\s+\d+$
        $patron = '/^\d+-TRIAL-(.+?)\s+\d+$/i';
        
        if (preg_match($patron, $texto, $matches)) {
            return trim($matches[1]);
        }
        
        // Patrón alternativo: texto termina con TRIAL y números (sin texto después)
        // Ejemplo: "texto original 88-TRIAL-algo 226" -> "texto original"
        $patronInverso = '/^(.+?)\s+\d+-TRIAL-.+$/i';
        
        if (preg_match($patronInverso, $texto, $matches)) {
            return trim($matches[1]);
        }
        
        // Patrón más flexible: cualquier cosa antes de TRIAL, luego el texto, luego números
        $patronFlexible = '/.*TRIAL[_\-\s]+(.*?)\s*\d+$/i';
        
        if (preg_match($patronFlexible, $texto, $matches)) {
            return trim($matches[1]);
        }
        
        // Patrón muy flexible: buscar TRIAL y extraer lo que viene después hasta números
        $patronMuyFlexible = '/.*TRIAL[_\-\s]+([^0-9]+)/i';
        
        if (preg_match($patronMuyFlexible, $texto, $matches)) {
            return trim($matches[1]);
        }
        
        // Si no encuentra el patrón, devolver el texto original
        return $texto;
    }
}
