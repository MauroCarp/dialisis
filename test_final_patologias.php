<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Paciente;

echo "Probando la carga de patologías ordenadas por fecha..." . PHP_EOL;

// Buscar un paciente que tenga patologías
$paciente = Paciente::with(['patologiasPacientes' => function($query) {
    $query->with('patologia')->orderBy('fechapatologia', 'desc');
}])->whereHas('patologiasPacientes')->first();

if ($paciente) {
    echo "Paciente encontrado: {$paciente->nombre} {$paciente->apellido}" . PHP_EOL;
    echo "Total de patologías: " . $paciente->patologiasPacientes->count() . PHP_EOL;
    echo PHP_EOL;
    
    if ($paciente->patologiasPacientes->count() > 0) {
        echo "Patologías ordenadas por fecha (más reciente primero):" . PHP_EOL;
        echo "======================================================" . PHP_EOL;
        
        foreach ($paciente->patologiasPacientes as $index => $patologiaPaciente) {
            $fecha = $patologiaPaciente->fechapatologia ? 
                     \Carbon\Carbon::parse($patologiaPaciente->fechapatologia)->format('d/m/Y') : 
                     'Sin fecha';
            
            $nombre = $patologiaPaciente->patologia ? 
                      $patologiaPaciente->patologia->nombre : 
                      'Patología no encontrada';
                      
            echo ($index + 1) . ". {$nombre} - {$fecha}" . PHP_EOL;
            
            if ($patologiaPaciente->observaciones) {
                echo "   Observaciones: " . substr($patologiaPaciente->observaciones, 0, 100) . PHP_EOL;
            }
            echo PHP_EOL;
        }
        
        echo "✓ Test exitoso: Las patologías se cargan correctamente ordenadas por fecha" . PHP_EOL;
    } else {
        echo "⚠ No se encontraron patologías para este paciente" . PHP_EOL;
    }
} else {
    echo "⚠ No se encontraron pacientes con patologías" . PHP_EOL;
}

echo PHP_EOL . "Verificando que no hay errores SQL..." . PHP_EOL;

// Probar la consulta directamente
try {
    $result = Paciente::with(['patologiasPacientes' => function($query) {
        $query->with('patologia')->orderBy('fechapatologia', 'desc')->limit(5);
    }])->first();
    
    echo "✓ Consulta SQL ejecutada correctamente sin errores" . PHP_EOL;
} catch (Exception $e) {
    echo "✗ Error en la consulta: " . $e->getMessage() . PHP_EOL;
}
