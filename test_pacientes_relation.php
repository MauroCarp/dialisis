<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Paciente;

// Verificar que la relación esté funcionando
$paciente = Paciente::with('obrasSociales')->first();

if ($paciente) {
    echo "Paciente encontrado: {$paciente->nombre} {$paciente->apellido}" . PHP_EOL;
    echo "Obras sociales: " . $paciente->obrasSociales->count() . PHP_EOL;
    
    foreach ($paciente->obrasSociales as $obra) {
        echo "- {$obra->abreviatura}: Afiliado: {$obra->pivot->nroafiliado}" . PHP_EOL;
    }
} else {
    echo "No se encontraron pacientes" . PHP_EOL;
}
