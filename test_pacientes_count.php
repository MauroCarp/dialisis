<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Paciente;

// Verificar cuántos pacientes hay
$totalPacientes = Paciente::count();
echo "Total de pacientes: {$totalPacientes}" . PHP_EOL;

if ($totalPacientes > 0) {
    $paciente = Paciente::with('obrasSociales')->first();
    echo "Primer paciente: {$paciente->nombre} {$paciente->apellido}" . PHP_EOL;
    echo "Obras sociales: " . $paciente->obrasSociales->count() . PHP_EOL;
} else {
    echo "No hay pacientes en la base de datos" . PHP_EOL;
}
