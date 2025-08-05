<?php

namespace App\Http\Controllers;

use App\Models\AnalisisSemestral;
use App\Models\Paciente;
use Illuminate\Http\Request;

class AnalisisSemestralController extends Controller
{
    public function store(Request $request, $pacienteId)
    {
        $request->validate([
            'fechaanalisis' => 'required|date',
            'calcio' => 'nullable|numeric',
            'fosforo' => 'nullable|numeric',
            'pth' => 'nullable|numeric',
            'albumina' => 'nullable|numeric',
            'ferritina' => 'nullable|numeric',
            'saturacion_transferrina' => 'nullable|numeric',
        ]);

        $paciente = Paciente::findOrFail($pacienteId);

        AnalisisSemestral::create([
            'id_paciente' => $paciente->id,
            'fechaanalisis' => $request->fechaanalisis,
            'calcio' => $request->calcio,
            'fosforo' => $request->fosforo,
            'pth' => $request->pth,
            'albumina' => $request->albumina,
            'ferritina' => $request->ferritina,
            'saturacion_transferrina' => $request->saturacion_transferrina,
        ]);

        return redirect()->route('pacientes.show', $paciente->id)
            ->with('success', 'Análisis semestral registrado correctamente.');
    }
}
