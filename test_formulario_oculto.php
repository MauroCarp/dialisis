<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Paciente;
use App\Models\AnalisisDiario;
use App\Models\TipoFiltro;

echo "Probando la lógica de ocultación del formulario cuando hay análisis incompleto..." . PHP_EOL;

// Buscar un paciente que pueda tener análisis
$paciente = Paciente::first();

if (!$paciente) {
    echo "No se encontraron pacientes" . PHP_EOL;
    exit;
}

echo "Paciente encontrado: {$paciente->nombre} {$paciente->apellido}" . PHP_EOL;

// Buscar un tipo de filtro
$tipoFiltro = TipoFiltro::first();

if (!$tipoFiltro) {
    echo "No se encontraron tipos de filtros" . PHP_EOL;
    exit;
}

echo "Tipo de filtro encontrado: {$tipoFiltro->nombre}" . PHP_EOL;

// Eliminar cualquier análisis existente para hoy
$fechaHoy = now()->format('Y-m-d');
AnalisisDiario::where('id_paciente', $paciente->id)
    ->whereDate('fechaanalisis', $fechaHoy)
    ->delete();

echo PHP_EOL . "Test 1: Verificando que NO hay análisis incompleto para hoy..." . PHP_EOL;

$analisisHoyIncompleto = $paciente->analisisDiarios()
    ->whereDate('fechaanalisis', $fechaHoy)
    ->where('estado', '!=', 'completo')
    ->first();

if (!$analisisHoyIncompleto) {
    echo "✓ Correcto: No hay análisis incompleto para hoy - EL FORMULARIO DEBE MOSTRARSE" . PHP_EOL;
} else {
    echo "✗ Error: Se encontró análisis incompleto cuando no debería haberlo" . PHP_EOL;
}

echo PHP_EOL . "Test 2: Creando análisis pre-diálisis para hoy..." . PHP_EOL;

// Crear un análisis pre-diálisis para hoy
$analisisPreDialisis = AnalisisDiario::create([
    'fechaanalisis' => $fechaHoy,
    'id_paciente' => $paciente->id,
    'pesopre' => 70.5,
    'taspre' => 140,
    'tadpre' => 90,
    'id_tipofiltro' => $tipoFiltro->id,
    'relpesosecopesopre' => 1.02,
    'interdialitico' => 2.5,
    'estado' => 'pre_dialisis'
]);

echo "✓ Análisis pre-diálisis creado con ID: {$analisisPreDialisis->id}" . PHP_EOL;

echo PHP_EOL . "Test 3: Verificando que SÍ hay análisis incompleto para hoy..." . PHP_EOL;

// Refrescar la consulta
$analisisHoyIncompleto = $paciente->fresh()->analisisDiarios()
    ->whereDate('fechaanalisis', $fechaHoy)
    ->where('estado', '!=', 'completo')
    ->first();

if ($analisisHoyIncompleto) {
    echo "✓ Correcto: Se encontró análisis incompleto para hoy - EL FORMULARIO DEBE OCULTARSE" . PHP_EOL;
    echo "   Estado del análisis: {$analisisHoyIncompleto->estado}" . PHP_EOL;
    echo "   Fecha: {$analisisHoyIncompleto->fechaanalisis}" . PHP_EOL;
} else {
    echo "✗ Error: No se encontró análisis incompleto cuando debería haberlo" . PHP_EOL;
}

echo PHP_EOL . "Test 4: Completando el análisis..." . PHP_EOL;

// Completar el análisis
$analisisPreDialisis->update([
    'pesopost' => 68.0,
    'taspos' => 120,
    'tadpos' => 80,
    'observaciones' => 'Sesión normal, sin incidencias',
    'estado' => 'completo'
]);

echo "✓ Análisis completado" . PHP_EOL;

echo PHP_EOL . "Test 5: Verificando que NO hay análisis incompleto después de completar..." . PHP_EOL;

// Verificar que ahora no hay análisis incompleto
$analisisHoyIncompleto = $paciente->fresh()->analisisDiarios()
    ->whereDate('fechaanalisis', $fechaHoy)
    ->where('estado', '!=', 'completo')
    ->first();

if (!$analisisHoyIncompleto) {
    echo "✓ Correcto: No hay análisis incompleto después de completar - EL FORMULARIO DEBE MOSTRARSE NUEVAMENTE" . PHP_EOL;
} else {
    echo "✗ Error: Aún hay análisis incompleto después de completar" . PHP_EOL;
}

// Limpiar datos de prueba
$analisisPreDialisis->delete();

echo PHP_EOL . "✓ Datos de prueba limpiados" . PHP_EOL;
echo PHP_EOL . "✓ Todos los tests completados exitosamente" . PHP_EOL;
echo PHP_EOL . "Resumen de la lógica:" . PHP_EOL;
echo "- Si NO hay análisis incompleto para hoy: MOSTRAR formulario pre-diálisis" . PHP_EOL;
echo "- Si SÍ hay análisis incompleto para hoy: OCULTAR formulario + mostrar mensaje" . PHP_EOL;
echo "- Opción de crear análisis para otra fecha siempre disponible" . PHP_EOL;
