<?php

namespace App\Http\Controllers;

use App\Models\HistoriaClinica;
use App\Models\Paciente;
use Illuminate\Http\Request;

class HistoriaClinicaController extends Controller
{
    public function create($id)
    {
        $paciente = Paciente::findOrFail($id);
        return view('historias-clinicas.create', compact('paciente'));
    }

    public function store(Request $request, $id)
    {
        $paciente = Paciente::findOrFail($id);
        
        $validated = $request->validate([
            'fechahistoriaclinica' => 'required|date',
            'observaciones' => 'nullable|string'
        ]);

        $validated['id_paciente'] = $paciente->id;

        HistoriaClinica::create($validated);

        return redirect()
            ->route('pacientes.show', $paciente->id)
            ->with('success', 'Historia clínica creada exitosamente.');
    }
}
