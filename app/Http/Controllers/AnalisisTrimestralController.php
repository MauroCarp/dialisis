<?php

namespace App\Http\Controllers;

use App\Models\AnalisisTrimestral;
use App\Models\Paciente;
use Illuminate\Http\Request;

class AnalisisTrimestralController extends Controller
{
    public function store(Request $request, $pacienteId)
    {
        $request->validate([
            'fechaanalisis' => 'required|date',
            'protocolo' => 'nullable|string|max:100',
            'albumina' => 'nullable|numeric',
            'colesterol' => 'nullable|numeric',
            'trigliseridos' => 'nullable|numeric',
        ]);

        $paciente = Paciente::findOrFail($pacienteId);

        AnalisisTrimestral::create([
            'id_paciente' => $paciente->id,
            'fechaanalisis' => $request->fechaanalisis,
            'protocolo' => $request->protocolo,
            'albumina' => $request->albumina,
            'colesterol' => $request->colesterol,
            'trigliseridos' => $request->trigliseridos,
        ]);

        return redirect()->route('pacientes.show', $paciente->id)
            ->with('success', 'Análisis trimestral registrado correctamente.');
    }
}
