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
use App\Http\Controllers\AnalisisDiarioEtapasController;
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
Route::delete('/historias-clinicas/{id}', [HistoriaClinicaController::class, 'destroy'])->name('historias-clinicas.destroy');

// Rutas para historias clínicas de consultorio
Route::get('/pacientes/{id}/historias-clinicas-consultorio/create', [HistoriaClinicaConsultorioController::class, 'create'])->name('historias-clinicas-consultorio.create');
Route::post('/pacientes/{id}/historias-clinicas-consultorio', [HistoriaClinicaConsultorioController::class, 'store'])->name('historias-clinicas-consultorio.store');
Route::get('/historias-clinicas-consultorio/{id}/download', [HistoriaClinicaConsultorioController::class, 'download'])->name('historia-clinica-consultorio.download');
Route::delete('/historias-clinicas-consultorio/{id}', [HistoriaClinicaConsultorioController::class, 'destroy'])->name('historias-clinicas-consultorio.destroy');

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
// Rutas para análisis diarios
Route::post('/pacientes/{paciente}/analisis-diarios', [AnalisisDiarioController::class, 'store'])
    ->name('analisis-diarios.store');

// Rutas para análisis diarios en etapas
Route::post('/pacientes/{paciente}/analisis-diarios/pre-dialisis', [AnalisisDiarioEtapasController::class, 'storePreDialisis'])
    ->name('analisis-diarios.store-pre-dialisis');

Route::post('/pacientes/{paciente}/analisis-diarios/post-dialisis', [AnalisisDiarioEtapasController::class, 'storePostDialisis'])
    ->name('analisis-diarios.store-post-dialisis');

Route::get('/pacientes/{paciente}/analisis-diarios/incompletos', [AnalisisDiarioEtapasController::class, 'getAnalisisIncompletos'])
    ->name('analisis-diarios.incompletos');

Route::get('/pacientes/{paciente}/analisis-diarios/por-fecha', [AnalisisDiarioEtapasController::class, 'getAnalisisPorFecha'])
    ->name('analisis-diarios.por-fecha');

// Rutas para editar y eliminar análisis diarios
Route::get('/analisis-diarios/{id}/edit', [AnalisisDiarioEtapasController::class, 'edit'])
    ->name('analisis-diarios.edit');
Route::put('/analisis-diarios/{id}', [AnalisisDiarioEtapasController::class, 'update'])
    ->name('analisis-diarios.update');
Route::delete('/analisis-diarios/{id}', [AnalisisDiarioEtapasController::class, 'destroy'])
    ->name('analisis-diarios.destroy');

Route::post('/pacientes/{paciente}/analisis-mensuales', [AnalisisMensualController::class, 'store'])
    ->name('analisis-mensuales.store');
Route::get('/analisis-mensuales/{id}/edit', [AnalisisMensualController::class, 'edit'])
    ->name('analisis-mensuales.edit');
Route::put('/analisis-mensuales/{id}', [AnalisisMensualController::class, 'update'])
    ->name('analisis-mensuales.update');
Route::delete('/analisis-mensuales/{id}', [AnalisisMensualController::class, 'destroy'])
    ->name('analisis-mensuales.destroy');
Route::post('/pacientes/{paciente}/analisis-trimestrales', [AnalisisTrimestralController::class, 'store'])
    ->name('analisis-trimestrales.store');
Route::get('/analisis-trimestrales/{id}/edit', [AnalisisTrimestralController::class, 'edit'])
    ->name('analisis-trimestrales.edit');
Route::put('/analisis-trimestrales/{id}', [AnalisisTrimestralController::class, 'update'])
    ->name('analisis-trimestrales.update');
Route::delete('/analisis-trimestrales/{id}', [AnalisisTrimestralController::class, 'destroy'])
    ->name('analisis-trimestrales.destroy');
Route::post('/pacientes/{paciente}/analisis-semestrales', [AnalisisSemestralController::class, 'store'])
    ->name('analisis-semestrales.store');
Route::get('/analisis-semestrales/{id}/edit', [AnalisisSemestralController::class, 'edit'])
    ->name('analisis-semestrales.edit');
Route::put('/analisis-semestrales/{id}', [AnalisisSemestralController::class, 'update'])
    ->name('analisis-semestrales.update');
Route::delete('/analisis-semestrales/{id}', [AnalisisSemestralController::class, 'destroy'])
    ->name('analisis-semestrales.destroy');

// Ruta para descargar reportes
Route::get('/reportes/download/{filename}', function ($filename) {
    $path = storage_path("app/public/{$filename}");
    
    if (!file_exists($path)) {
        abort(404, 'Archivo no encontrado');
    }
    
    return response()->download($path);
})->name('reportes.download')->where('filename', '.*');