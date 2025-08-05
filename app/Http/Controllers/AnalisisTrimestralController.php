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
            'hepatitisb' => 'nullable|boolean',
            'hepatitisc' => 'nullable|boolean',
            'hiv' => 'nullable|boolean',
            'vdrl' => 'nullable|boolean',
        ]);

        $paciente = Paciente::findOrFail($pacienteId);

        AnalisisTrimestral::create([
            'id_paciente' => $paciente->id,
            'fechaanalisis' => $request->fechaanalisis,
            'hepatitisb' => $request->has('hepatitisb'),
            'hepatitisc' => $request->has('hepatitisc'),
            'hiv' => $request->has('hiv'),
            'vdrl' => $request->has('vdrl'),
        ]);

        return redirect()->route('pacientes.show', $paciente->id)
            ->with('success', 'Análisis trimestral registrado correctamente.');
    }
}
