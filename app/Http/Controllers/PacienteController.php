<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\PacienteConsultorio;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PacienteController extends Controller
{
    /**
     * Display the specified paciente.
     */
    public function show($id): View
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

        // Cargar las relaciones necesarias según el tipo de paciente
        if ($esPacienteConsultorio) {
            $paciente->load([
                'localidad.provincia',
                'obrasSociales',
                'accesosVasculares.tipoAccesoVascular',
                'historiasClinicasConsultorio' => function($query) {
                    $query->orderBy('fechahistoriaclinica', 'desc')->limit(10);
                }
            ]);
        } else {
            $paciente->load([
                'localidad.provincia',
                'tipoDocumento',
                'obrasSociales',
                'accesosVasculares.tipoAccesoVascular',
                'historiasClinicas' => function($query) {
                    $query->orderBy('fechahistoriaclinica', 'desc')->limit(10);
                }
            ]);
        }

        return view('pacientes.show', compact('paciente', 'esPacienteConsultorio'));
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
