<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\HistoriaClinicaController;
use App\Http\Controllers\HistoriaClinicaConsultorioController;
use App\Http\Controllers\AccesoVascularController;
use App\Http\Controllers\AnalisisDiarioController;
use App\Http\Controllers\AnalisisMensualController;
use App\Http\Controllers\AnalisisTrimestralController;
use App\Http\Controllers\AnalisisSemestralController;

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

Route::get('/pacientes/{paciente}', [PacienteController::class, 'show'])->name('pacientes.show');
Route::get('/pacientes/{paciente}/edit', [PacienteController::class, 'edit'])->name('pacientes.edit');
Route::put('/pacientes/{paciente}', [PacienteController::class, 'update'])->name('pacientes.update');

// Rutas para historias clínicas de diálisis
Route::get('/pacientes/{id}/historias-clinicas/create', [HistoriaClinicaController::class, 'create'])->name('historias-clinicas.create');
Route::post('/pacientes/{id}/historias-clinicas', [HistoriaClinicaController::class, 'store'])->name('historias-clinicas.store');

// Rutas para historias clínicas de consultorio
Route::get('/pacientes/{id}/historias-clinicas-consultorio/create', [HistoriaClinicaConsultorioController::class, 'create'])->name('historias-clinicas-consultorio.create');
Route::post('/pacientes/{id}/historias-clinicas-consultorio', [HistoriaClinicaConsultorioController::class, 'store'])->name('historias-clinicas-consultorio.store');

// Ruta para crear accesos vasculares
Route::post('/pacientes/{paciente}/accesos-vasculares', [AccesoVascularController::class, 'store'])
    ->name('accesos-vasculares.store');

// Rutas para análisis
Route::post('/pacientes/{paciente}/analisis-diarios', [AnalisisDiarioController::class, 'store'])
    ->name('analisis-diarios.store');
Route::post('/pacientes/{paciente}/analisis-mensuales', [AnalisisMensualController::class, 'store'])
    ->name('analisis-mensuales.store');
Route::post('/pacientes/{paciente}/analisis-trimestrales', [AnalisisTrimestralController::class, 'store'])
    ->name('analisis-trimestrales.store');
Route::post('/pacientes/{paciente}/analisis-semestrales', [AnalisisSemestralController::class, 'store'])
    ->name('analisis-semestrales.store');