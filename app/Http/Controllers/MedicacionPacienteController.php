<?php

namespace App\Http\Controllers;

use App\Models\MedicacionPaciente;
use App\Models\Paciente;
use App\Models\PacienteConsultorio;
use Illuminate\Http\Request;

class MedicacionPacienteController extends Controller
{
    public function store(Request $request, $pacienteId)
    {
        $request->validate([
            'fechamedicacion' => 'required|date',
            'id_medicacion' => 'required|exists:medicaciones,id',
            'cantidad' => 'nullable|numeric',
            'observaciones' => 'nullable|string',
        ]);

        MedicacionPaciente::create([
            'id_paciente' => $pacienteId,
            'fechamedicacion' => $request->fechamedicacion,
            'id_medicacion' => $request->id_medicacion,
            'cantidad' => $request->cantidad,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('pacientes.show', $pacienteId)
            ->with('success', 'Medicación registrada correctamente.')
            ->with('show_tab', 'medicaciones');
    }
}
