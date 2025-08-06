<?php

namespace App\Http\Controllers;

use App\Models\Internacion;
use App\Models\Paciente;
use Illuminate\Http\Request;

class InternacionController extends Controller
{
    public function store(Request $request, $pacienteId)
    {
        $request->validate([
            'fechainiciointernacion' => 'required|date',
            'fechafininternacion' => 'nullable|date|after_or_equal:fechainiciointernacion',
            'id_motivo_internacion' => 'required|exists:motivosinternaciones,id',
            'observaciones' => 'nullable|string',
        ]);

        Internacion::create([
            'id_paciente' => $pacienteId,
            'fechainiciointernacion' => $request->fechainiciointernacion,
            'fechafininternacion' => $request->fechafininternacion,
            'id_motivo_internacion' => $request->id_motivo_internacion,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('pacientes.show', $pacienteId)
            ->with('success', 'Internación registrada correctamente.');
    }
}
