<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\PacienteConsultorio;
use App\Models\TipoAccesoVascular;
use App\Models\TipoFiltro;
use App\Models\Cirujano;
use App\Models\Estudio;
use App\Models\MotivoInternacion;
use App\Models\Patologia;
use App\Models\Medicacion;
use App\Models\Vacuna;
use App\Models\AnalisisDiario;
use App\Models\AnalisisMensual;
use App\Models\AnalisisTrimestral;
use App\Models\AnalisisSemestral;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PacienteController extends Controller
{
    /**
     * Display the specified paciente.
     */
    public function show($id, Request $request): View
    {
        $esPacienteConsultorio = false;
        $paciente = null;

        // Si se especifica el tipo en la URL, buscar directamente en esa tabla
        if ($request->has('tipo') && $request->get('tipo') === 'consultorio') {
            $paciente = PacienteConsultorio::find($id);
            $esPacienteConsultorio = true;
        } else {
            // Primero intentar encontrar en la tabla pacientes
            $paciente = Paciente::find($id);
            
            // Si no se encuentra, buscar en pacientesconsultorio
            if (!$paciente) {
                $paciente = PacienteConsultorio::find($id);
                $esPacienteConsultorio = true;
            }
        }

        // Si no se encuentra en ninguna tabla, devolver 404
        if (!$paciente) {
            abort(404, 'Paciente no encontrado');
        }

        // Cargar las relaciones necesarias según el tipo de paciente
        if ($esPacienteConsultorio) {
            $paciente->load([
                'localidad.provincia',
                'obrasSociales',
                'accesosVasculares.tipoAccesoVascular',
                'historiasClinicasConsultorio' => function($query) {
                    $query->orderBy('fechahistoriaclinica', 'desc')->limit(10);
                },
                'estudiosPacientes' => function($query) {
                    $query->with('estudio')->orderBy('fechaestudio', 'desc')->limit(10);                    
                },
                'internaciones.motivoInternacion' => function($query) {
                    $query->orderBy('nombre', 'desc')->limit(10);
                },
                'patologiasPacientes' => function($query) {
                    $query->with('patologia')->orderBy('fechapatologia', 'desc')->limit(10);
                },
                'transfusiones' => function($query) {
                    $query->orderBy('fechatransfusion', 'desc')->limit(10);
                },
                'medicacionesPacientes.medicacion.tipoMedicacion' => function($query) {
                    $query->orderBy('nombre', 'desc')->limit(10);
                },
                'vacunasPacientes.vacuna' => function($query) {
                    $query->orderBy('fechavacuna', 'desc')->limit(10);
                },
                'vacunasPacientes.dosis' => function($query) {
                    $query->orderBy('fechadosis', 'desc');
                }
            ]);
        } else {
            $paciente->load([
                'localidad.provincia',
                'tipoDocumento',
                'obrasSociales',
                'accesosVasculares.tipoAccesoVascular',
                'estudiosPacientes' => function($query) {
                    $query->with('estudio')->orderBy('fechaestudio', 'desc')->limit(10);                    
                },
                'historiasClinicas' => function($query) {
                    $query->orderBy('fechahistoriaclinica', 'desc')->limit(10);
                },
                'patologiasPacientes' => function($query) {
                    $query->with('patologia')->orderBy('fechapatologia', 'desc')->limit(10);
                },
                'transfusiones' => function($query) {
                    $query->orderBy('fechatransfusion', 'desc')->limit(10);
                },
                'medicacionesPacientes' => function($query) {
                    $query->with('medicacion.tipoMedicacion')->orderBy('fechamedicacion', 'desc')->limit(10);
                },
                'vacunasPacientes.vacuna' => function($query) {
                    $query->orderBy('nombre', 'desc')->limit(10);
                },
                'vacunasPacientes.dosis' => function($query) {
                    $query->orderBy('fechadosis', 'desc');
                }
            ]);
        }

        // Obtener tipos de acceso vascular para el modal
        $tiposAccesoVascular = TipoAccesoVascular::all();
        
        // Obtener cirujanos para el modal
        $cirujanos = Cirujano::where(function($query) {
            $query->whereNull('fechabaja')
                  ->orWhere('fechabaja', '<=', '1900-01-02'); // Considerar 1900-01-01 como "activo"
        })->orderBy('nombre')->orderBy('apellido')->get();
        
        // Obtener catálogos para los nuevos módulos
        $estudios = Estudio::where(function($query) {
            $query->whereNull('fechabaja')
                  ->orWhere('fechabaja', '<=', '1900-01-02');
        })->orderBy('nombre')->get();
        
        $motivosInternacion = MotivoInternacion::where(function($query) {
            $query->whereNull('fechabaja')
                  ->orWhere('fechabaja', '<=', '1900-01-02');
        })->orderBy('nombre')->get();
        
        $patologias = Patologia::where(function($query) {
            $query->whereNull('fechabaja')
                  ->orWhere('fechabaja', '<=', '1900-01-02');
        })->orderBy('nombre')->get();
        
        // Obtener medicaciones y vacunas para los nuevos módulos
        $medicaciones = \App\Models\Medicacion::where(function($query) {
            $query->whereNull('fechabaja')
                  ->orWhere('fechabaja', '<=', '1900-01-02');
        })->with('tipoMedicacion')->orderBy('nombre')->get();
        
        $vacunas = \App\Models\Vacuna::where(function($query) {
            $query->whereNull('fechabaja')
                  ->orWhere('fechabaja', '<=', '1900-01-02');
        })->orderBy('nombre')->get();
        
        // Obtener tipos de filtro para los análisis diarios
        $tiposFiltros = TipoFiltro::all();
        
        // Obtener análisis del paciente
        $analisisData = [];
        if (!$esPacienteConsultorio) {
            $analisisData = [
                'diarios' => AnalisisDiario::with('tipoFiltro')->where('id_paciente', $paciente->id)
                    ->orderBy('fechaanalisis', 'desc')
                    ->limit(10)
                    ->get(),
                'mensuales' => AnalisisMensual::where('id_paciente', $paciente->id)
                    ->orderBy('fechaanalisis', 'desc')
                    ->limit(10)
                    ->get(),
                'trimestrales' => AnalisisTrimestral::where('id_paciente', $paciente->id)
                    ->orderBy('fechaanalisis', 'desc')
                    ->limit(10)
                    ->get(),
                'semestrales' => AnalisisSemestral::where('id_paciente', $paciente->id)
                    ->orderBy('fechaanalisis', 'desc')
                    ->limit(10)
                    ->get()
            ];
        }
        
        // Debug: verificar que se obtienen los datos
        if ($tiposAccesoVascular->isEmpty()) {
            logger('WARNING: No se encontraron tipos de acceso vascular');
        } else {
            logger('INFO: Se encontraron ' . $tiposAccesoVascular->count() . ' tipos de acceso vascular');
        }

        return view('pacientes.show', compact('paciente', 'esPacienteConsultorio', 'tiposAccesoVascular', 'cirujanos', 'estudios', 'motivosInternacion', 'patologias', 'medicaciones', 'vacunas', 'tiposFiltros', 'analisisData'));
    }

    /**
     * Show the form for editing the specified paciente.
     */
    public function edit($id): View
    {
        // Primero intentar encontrar en la tabla pacientes
        $paciente = Paciente::find($id);
        $esPacienteConsultorio = false;

        // Si no se encuentra, buscar en pacientesconsultorio
        if (!$paciente) {
            $paciente = PacienteConsultorio::find($id);
            $esPacienteConsultorio = true;
        }

        // Si no se encuentra en ninguna tabla, devolver 404
        if (!$paciente) {
            abort(404, 'Paciente no encontrado');
        }

        return view('pacientes.edit', compact('paciente', 'esPacienteConsultorio'));
    }

    /**
     * Update the specified paciente in storage.
     */
    public function update(Request $request, $id)
    {
        // Primero intentar encontrar en la tabla pacientes
        $paciente = Paciente::find($id);
        $esPacienteConsultorio = false;

        // Si no se encuentra, buscar en pacientesconsultorio
        if (!$paciente) {
            $paciente = PacienteConsultorio::find($id);
            $esPacienteConsultorio = true;
        }

        // Si no se encuentra en ninguna tabla, devolver 404
        if (!$paciente) {
            abort(404, 'Paciente no encontrado');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dnicuitcuil' => 'nullable|string|max:20',
            'fechanacimiento' => 'nullable|date',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:500',
            'id_localidad' => 'nullable|exists:localidades,id',
            'pesoseco' => 'nullable|numeric|min:0',
            'talla' => 'nullable|numeric|min:0',
            'gruposanguineo' => 'nullable|string|max:10',
            'fumador' => 'boolean',
            'insulinodependiente' => 'boolean',
        ]);

        $paciente->update($validated);

        return redirect()->back()->with('success', 'Paciente actualizado correctamente.');
    }
}
