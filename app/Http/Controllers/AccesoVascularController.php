<?php

namespace App\Http\Controllers;

use App\Models\AccesoVascular;
use App\Models\Paciente;
use Illuminate\Http\Request;

class AccesoVascularController extends Controller
{
    public function store(Request $request, Paciente $paciente)
    {
        $request->validate([
            'tipo_acceso_vascular_id' => 'required|exists:tipos_acceso_vascular,id',
            'fechaacceso' => 'required|date',
            'observaciones' => 'nullable|string',
        ]);

        AccesoVascular::create([
            'paciente_id' => $paciente->id,
            'tipo_acceso_vascular_id' => $request->tipo_acceso_vascular_id,
            'fechaacceso' => $request->fechaacceso,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('pacientes.show', $paciente)
                         ->with('success', 'Acceso vascular creado exitosamente.');
    }
}