<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/pacientes/{paciente}/edit', [\App\Http\Controllers\PacienteController::class, 'edit'])->name('pacientes.edit');
Route::put('/pacientes/{paciente}', [\App\Http\Controllers\PacienteController::class, 'update'])->name('pacientes.update');