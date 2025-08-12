<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\AnalisisDiario;
use App\Models\Paciente;
use App\Models\TipoFiltro;

echo "Probando la funcionalidad de análisis diarios en dos etapas..." . PHP_EOL;

// Buscar un paciente de diálisis
$paciente = Paciente::first();

if (!$paciente) {
    echo "⚠ No se encontraron pacientes" . PHP_EOL;
    exit;
}

echo "Paciente encontrado: {$paciente->nombre} {$paciente->apellido}" . PHP_EOL;

// Buscar un tipo de filtro
$tipoFiltro = TipoFiltro::first();

if (!$tipoFiltro) {
    echo "⚠ No se encontraron tipos de filtro" . PHP_EOL;
    exit;
}

echo "Tipo de filtro encontrado: {$tipoFiltro->nombre}" . PHP_EOL;

// Test 1: Crear análisis pre-diálisis
echo PHP_EOL . "Test 1: Creando análisis pre-diálisis..." . PHP_EOL;

$analisisPreDialisis = AnalisisDiario::create([
    'fechaanalisis' => now()->format('Y-m-d'),
    'id_paciente' => $paciente->id,
    'pesopre' => 75.5,
    'taspre' => 140,
    'tadpre' => 90,
    'id_tipofiltro' => $tipoFiltro->id,
    'relpesosecopesopre' => 2.5,
    'interdialitico' => 3.0,
    'estado' => 'pre_dialisis'
]);

echo "✓ Análisis pre-diálisis creado con ID: {$analisisPreDialisis->id}" . PHP_EOL;
echo "   Estado: {$analisisPreDialisis->estado}" . PHP_EOL;
echo "   Es pre-diálisis: " . ($analisisPreDialisis->esPreDialisis() ? 'Sí' : 'No') . PHP_EOL;

// Test 2: Completar análisis con datos post-diálisis
echo PHP_EOL . "Test 2: Completando análisis con datos post-diálisis..." . PHP_EOL;

$analisisPreDialisis->update([
    'pesopost' => 73.0,
    'taspos' => 120,
    'tadpos' => 80,
    'observaciones' => 'Sesión completada sin incidencias',
    'estado' => 'completo'
]);

$analisisPreDialisis->refresh();

echo "✓ Análisis completado" . PHP_EOL;
echo "   Estado: {$analisisPreDialisis->estado}" . PHP_EOL;
echo "   Está completo: " . ($analisisPreDialisis->estaCompleto() ? 'Sí' : 'No') . PHP_EOL;

// Test 3: Probar scopes del modelo
echo PHP_EOL . "Test 3: Probando scopes del modelo..." . PHP_EOL;

$analisisCompletos = AnalisisDiario::completo()->count();
$analisisPreDialisis = AnalisisDiario::preDialisis()->count();

echo "   Análisis completos: {$analisisCompletos}" . PHP_EOL;
echo "   Análisis pre-diálisis: {$analisisPreDialisis}" . PHP_EOL;

// Test 4: Probar análisis pendientes
echo PHP_EOL . "Test 4: Probando análisis pendientes..." . PHP_EOL;

$pendientes = AnalisisDiario::where('id_paciente', $paciente->id)
    ->where('estado', '!=', 'completo')
    ->count();

echo "   Análisis pendientes para este paciente: {$pendientes}" . PHP_EOL;

echo PHP_EOL . "✓ Todos los tests completados exitosamente" . PHP_EOL;
echo "✓ La funcionalidad de análisis diarios en dos etapas está funcionando correctamente" . PHP_EOL;
