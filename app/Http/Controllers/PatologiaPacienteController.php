<?php

namespace App\Http\Controllers;

use App\Models\PatologiaPaciente;
use App\Models\Paciente;
use Illuminate\Http\Request;

class PatologiaPacienteController extends Controller
{
    public function store(Request $request, $pacienteId)
    {
        $request->validate([
            'fechapatologia' => 'required|date',
            'id_patologia' => 'required|exists:patologias,id',
            'observaciones' => 'nullable|string',
        ]);

        PatologiaPaciente::create([
            'id_paciente' => $pacienteId,
            'fechapatologia' => $request->fechapatologia,
            'id_patologia' => $request->id_patologia,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('pacientes.show', $pacienteId)
            ->with('success', 'Patología registrada correctamente.')
            ->with('show_tab', 'patologias');
    }
}
