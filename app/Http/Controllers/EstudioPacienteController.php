<?php

namespace App\Http\Controllers;

use App\Models\EstudioPaciente;
use App\Models\Paciente;
use Illuminate\Http\Request;

class EstudioPacienteController extends Controller
{
    public function store(Request $request, $pacienteId)
    {
        $request->validate([
            'fechaestudio' => 'required|date',
            'id_estudio' => 'required|exists:estudios,id',
            'observaciones' => 'nullable|string',
        ]);

        EstudioPaciente::create([
            'id_paciente' => $pacienteId,
            'fechaestudio' => $request->fechaestudio,
            'id_estudio' => $request->id_estudio,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('pacientes.show', $pacienteId)
            ->with('success', 'Estudio registrado correctamente.');
    }
}
