<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\TipoAccesoVascular;
use App\Models\AccesoVascular;
use App\Models\Paciente;

echo "Verificando estructura de tablas y relaciones de accesos vasculares..." . PHP_EOL;

// Verificar que la tabla existe y tiene datos
try {
    $tiposAcceso = TipoAccesoVascular::all();
    echo "✓ Tabla 'tiposaccesosvasculares' encontrada con " . $tiposAcceso->count() . " registros" . PHP_EOL;
    
    if ($tiposAcceso->count() > 0) {
        echo "Tipos de acceso disponibles:" . PHP_EOL;
        foreach ($tiposAcceso->take(5) as $tipo) {
            echo "  - ID: {$tipo->id}, Nombre: {$tipo->nombre}" . PHP_EOL;
        }
    }
} catch (Exception $e) {
    echo "✗ Error al acceder a la tabla tiposaccesosvasculares: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

echo PHP_EOL;

// Verificar relaciones
try {
    $paciente = Paciente::with('accesosVasculares.tipoAccesoVascular')->first();
    if ($paciente) {
        echo "✓ Paciente encontrado: {$paciente->nombre} {$paciente->apellido}" . PHP_EOL;
        echo "✓ Accesos vasculares: " . $paciente->accesosVasculares->count() . PHP_EOL;
        
        if ($paciente->accesosVasculares->count() > 0) {
            foreach ($paciente->accesosVasculares->take(3) as $acceso) {
                $tipo = $acceso->tipoAccesoVascular ? $acceso->tipoAccesoVascular->nombre : 'Sin tipo';
                $fecha = $acceso->fechaacceso ? $acceso->fechaacceso->format('d/m/Y') : 'Sin fecha';
                echo "  - {$tipo} ({$fecha})" . PHP_EOL;
            }
        }
    }
} catch (Exception $e) {
    echo "✗ Error al verificar relaciones: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

echo PHP_EOL . "✓ Verificación completada exitosamente" . PHP_EOL;
