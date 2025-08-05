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
            'protocolo' => 'nullable|string|max:100',
            'hbsag' => 'nullable|boolean',
            'antihbsag' => 'nullable|boolean',
            'valorantihbsag' => 'nullable|numeric',
            'antihcv' => 'nullable|boolean',
            'antihiv' => 'nullable|boolean',
            'anticore' => 'nullable|boolean',
            'pth' => 'nullable|numeric',
            'ferritina' => 'nullable|numeric',
            'ferremia' => 'nullable|numeric',
        ]);

        $paciente = Paciente::findOrFail($pacienteId);

        AnalisisSemestral::create([
            'id_paciente' => $paciente->id,
            'fechaanalisis' => $request->fechaanalisis,
            'protocolo' => $request->protocolo,
            'hbsag' => $request->has('hbsag'),
            'antihbsag' => $request->has('antihbsag'),
            'valorantihbsag' => $request->valorantihbsag,
            'antihcv' => $request->has('antihcv'),
            'antihiv' => $request->has('antihiv'),
            'anticore' => $request->has('anticore'),
            'pth' => $request->pth,
            'ferritina' => $request->ferritina,
            'ferremia' => $request->ferremia,
        ]);

        return redirect()->route('pacientes.show', $paciente->id)
            ->with('success', 'Análisis semestral registrado correctamente.');
    }
}
