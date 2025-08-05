<?php

namespace App\Http\Controllers;

use App\Models\AnalisisDiario;
use App\Models\Paciente;
use Illuminate\Http\Request;

class AnalisisDiarioController extends Controller
{
    public function store(Request $request, $pacienteId)
    {
        $request->validate([
            'fechaanalisis' => 'required|date',
            'id_tipofiltro' => 'required|exists:tiposfiltros,id',
            'pesopre' => 'nullable|numeric',
            'pesopost' => 'nullable|numeric',
            'taspre' => 'nullable|integer',
            'taspos' => 'nullable|integer',
            'tadpre' => 'nullable|integer',
            'tadpos' => 'nullable|integer',
            'relpesosecopesopre' => 'nullable|numeric',
            'interdialitico' => 'nullable|numeric',
        ]);

        $paciente = Paciente::findOrFail($pacienteId);

        AnalisisDiario::create([
            'id_paciente' => $paciente->id,
            'fechaanalisis' => $request->fechaanalisis,
            'id_tipofiltro' => $request->id_tipofiltro,
            'pesopre' => $request->pesopre,
            'pesopost' => $request->pesopost,
            'taspre' => $request->taspre,
            'taspos' => $request->taspos,
            'tadpre' => $request->tadpre,
            'tadpos' => $request->tadpos,
            'relpesosecopesopre' => $request->relpesosecopesopre,
            'interdialitico' => $request->interdialitico,
        ]);

        return redirect()->route('pacientes.show', $paciente->id)
            ->with('success', 'Análisis diario registrado correctamente.');
    }
}
