<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Paciente;

// Probar la carga de patologías ordenadas correctamente
$paciente = Paciente::with(['patologiasPacientes' => function($query) {
    $query->with('patologia')->orderBy('fechapatologia', 'desc')->limit(10);
}])->first();

if ($paciente) {
    echo "Paciente: {$paciente->nombre} {$paciente->apellido}" . PHP_EOL;
    echo "Patologías: " . $paciente->patologiasPacientes->count() . PHP_EOL;
    
    foreach ($paciente->patologiasPacientes as $patologiaPaciente) {
        echo "- {$patologiaPaciente->patologia->nombre}: {$patologiaPaciente->fechapatologia}" . PHP_EOL;
    }
} else {
    echo "No se encontraron pacientes" . PHP_EOL;
}
