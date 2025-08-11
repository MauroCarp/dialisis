<?php

namespace App\Http\Controllers;

use App\Models\Transfusion;
use App\Models\Paciente;
use Illuminate\Http\Request;

class TransfusionController extends Controller
{
    public function store(Request $request, $pacienteId)
    {
        $request->validate([
            'fechatransfusion' => 'required|date',
            'observaciones' => 'nullable|string',
        ]);

        Transfusion::create([
            'id_paciente' => $pacienteId,
            'fechatransfusion' => $request->fechatransfusion,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('pacientes.show', $pacienteId)
            ->with('success', 'Transfusión registrada correctamente.')
            ->with('show_tab', 'transfusiones');
    }
}
