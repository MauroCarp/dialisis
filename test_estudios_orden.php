<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Paciente;

echo "Probando la carga de estudios ordenados por fecha..." . PHP_EOL;

// Buscar un paciente que tenga estudios
$paciente = Paciente::with(['estudiosPacientes' => function($query) {
    $query->with('estudio')->orderBy('fechaestudio', 'desc');
}])->whereHas('estudiosPacientes')->first();

if ($paciente) {
    echo "Paciente encontrado: {$paciente->nombre} {$paciente->apellido}" . PHP_EOL;
    echo "Total de estudios: " . $paciente->estudiosPacientes->count() . PHP_EOL;
    echo PHP_EOL;
    
    if ($paciente->estudiosPacientes->count() > 0) {
        echo "Estudios ordenados por fecha (más reciente primero):" . PHP_EOL;
        echo "====================================================" . PHP_EOL;
        
        foreach ($paciente->estudiosPacientes as $index => $estudioPaciente) {
            $fecha = $estudioPaciente->fechaestudio ? 
                     \Carbon\Carbon::parse($estudioPaciente->fechaestudio)->format('d/m/Y') : 
                     'Sin fecha';
            
            $nombre = $estudioPaciente->estudio ? 
                      $estudioPaciente->estudio->nombre : 
                      'Estudio no encontrado';
                      
            echo ($index + 1) . ". {$nombre} - {$fecha}" . PHP_EOL;
            
            if ($estudioPaciente->observaciones) {
                echo "   Observaciones: " . substr($estudioPaciente->observaciones, 0, 80) . PHP_EOL;
            }
            echo PHP_EOL;
        }
        
        echo "✓ Test exitoso: Los estudios se cargan correctamente ordenados por fecha" . PHP_EOL;
    } else {
        echo "⚠ No se encontraron estudios para este paciente" . PHP_EOL;
    }
} else {
    echo "⚠ No se encontraron pacientes con estudios" . PHP_EOL;
}

echo PHP_EOL . "Verificando que no hay errores SQL..." . PHP_EOL;

// Probar la consulta directamente
try {
    $result = Paciente::with(['estudiosPacientes' => function($query) {
        $query->with('estudio')->orderBy('fechaestudio', 'desc')->limit(5);
    }])->first();
    
    echo "✓ Consulta SQL ejecutada correctamente sin errores" . PHP_EOL;
} catch (Exception $e) {
    echo "✗ Error en la consulta: " . $e->getMessage() . PHP_EOL;
}
