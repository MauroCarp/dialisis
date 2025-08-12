<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Verificando rutas de análisis diarios..." . PHP_EOL;

// Verificar que las rutas existen
try {
    $routePreDialisis = route('analisis-diarios.store-pre-dialisis', ['paciente' => 1]);
    echo "✓ Ruta pre-dialisis: $routePreDialisis" . PHP_EOL;
} catch (Exception $e) {
    echo "✗ Error con ruta pre-dialisis: " . $e->getMessage() . PHP_EOL;
}

try {
    $routePostDialisis = route('analisis-diarios.store-post-dialisis', ['paciente' => 1]);
    echo "✓ Ruta post-dialisis: $routePostDialisis" . PHP_EOL;
} catch (Exception $e) {
    echo "✗ Error con ruta post-dialisis: " . $e->getMessage() . PHP_EOL;
}

try {
    $routeIncompletos = route('analisis-diarios.incompletos', ['paciente' => 1]);
    echo "✓ Ruta incompletos: $routeIncompletos" . PHP_EOL;
} catch (Exception $e) {
    echo "✗ Error con ruta incompletos: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . "Verificación completada." . PHP_EOL;
