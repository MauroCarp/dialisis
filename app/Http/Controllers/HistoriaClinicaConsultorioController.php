<?php

namespace App\Http\Controllers;

use App\Models\HistoriaClinicaConsultorio;
use App\Models\PacienteConsultorio;
use Illuminate\Http\Request;

class HistoriaClinicaConsultorioController extends Controller
{
    public function create($id)
    {
        $paciente = PacienteConsultorio::findOrFail($id);
        return view('historias-clinicas-consultorio.create', compact('paciente'));
    }

    public function store(Request $request, $id)
    {
        $paciente = PacienteConsultorio::findOrFail($id);
        
        $validated = $request->validate([
            'fechahistoriaclinica' => 'required|date',
            'observaciones' => 'nullable|string'
        ]);

        $validated['id_paciente'] = $paciente->id;

        HistoriaClinicaConsultorio::create($validated);

        return redirect()
            ->route('pacientes.show', $paciente->id)
            ->with('success', 'Historia clínica de consultorio creada exitosamente.');
    }
}
