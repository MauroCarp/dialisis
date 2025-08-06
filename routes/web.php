<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\HistoriaClinicaController;
use App\Http\Controllers\HistoriaClinicaConsultorioController;
use App\Http\Controllers\AccesoVascularController;
use App\Http\Controllers\CirujanoController;
use App\Http\Controllers\EstudioPacienteController;
use App\Http\Controllers\InternacionController;
use App\Http\Controllers\PatologiaPacienteController;
use App\Http\Controllers\TransfusionController;
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
Route::get('/historias-clinicas/{id}/download', [HistoriaClinicaController::class, 'download'])->name('historias-clinicas.download');

// Rutas para historias clínicas de consultorio
Route::get('/pacientes/{id}/historias-clinicas-consultorio/create', [HistoriaClinicaConsultorioController::class, 'create'])->name('historias-clinicas-consultorio.create');
Route::post('/pacientes/{id}/historias-clinicas-consultorio', [HistoriaClinicaConsultorioController::class, 'store'])->name('historias-clinicas-consultorio.store');
Route::get('/historias-clinicas-consultorio/{id}/download', [HistoriaClinicaConsultorioController::class, 'download'])->name('historia-clinica-consultorio.download');

// Ruta para crear accesos vasculares
Route::post('/pacientes/{paciente}/accesos-vasculares', [AccesoVascularController::class, 'store'])
    ->name('accesos-vasculares.store');

// Ruta para crear cirujanos
Route::post('/cirujanos', [CirujanoController::class, 'store'])->name('cirujanos.store');

// Rutas para estudios de pacientes
Route::post('/pacientes/{paciente}/estudios-pacientes', [EstudioPacienteController::class, 'store'])
    ->name('estudios-pacientes.store');

// Rutas para internaciones
Route::post('/pacientes/{paciente}/internaciones', [InternacionController::class, 'store'])
    ->name('internaciones.store');

// Rutas para patologías de pacientes
Route::post('/pacientes/{paciente}/patologias-pacientes', [PatologiaPacienteController::class, 'store'])
    ->name('patologias-pacientes.store');

// Rutas para transfusiones
Route::post('/pacientes/{pacienteId}/transfusiones', [App\Http\Controllers\TransfusionController::class, 'store'])
    ->name('transfusiones.store');

// Rutas para medicaciones de pacientes
Route::post('/pacientes/{pacienteId}/medicaciones', [App\Http\Controllers\MedicacionPacienteController::class, 'store'])
    ->name('medicaciones-pacientes.store');

// Rutas para vacunas de pacientes
Route::post('/pacientes/{pacienteId}/vacunas', [App\Http\Controllers\VacunaPacienteController::class, 'store'])
    ->name('vacunas-pacientes.store');

// Rutas para dosis de vacunas
Route::post('/vacunas-pacientes/{vacunaPacienteId}/dosis', [App\Http\Controllers\DosisController::class, 'store'])
    ->name('dosis.store');

// Rutas para análisis
Route::post('/pacientes/{paciente}/analisis-diarios', [AnalisisDiarioController::class, 'store'])
    ->name('analisis-diarios.store');
Route::post('/pacientes/{paciente}/analisis-mensuales', [AnalisisMensualController::class, 'store'])
    ->name('analisis-mensuales.store');
Route::post('/pacientes/{paciente}/analisis-trimestrales', [AnalisisTrimestralController::class, 'store'])
    ->name('analisis-trimestrales.store');
Route::post('/pacientes/{paciente}/analisis-semestrales', [AnalisisSemestralController::class, 'store'])
    ->name('analisis-semestrales.store');