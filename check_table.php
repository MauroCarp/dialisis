<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;

$columns = Schema::getColumnListing('pacientesconsultorioobrassociales');
echo 'Columnas en la tabla pacientesconsultorioobrassociales:' . PHP_EOL;
foreach($columns as $column) {
    echo '- ' . $column . PHP_EOL;
}
