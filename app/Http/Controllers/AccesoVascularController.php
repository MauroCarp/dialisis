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
            'id_tipoacceso' => 'required|exists:tiposaccesosvasculares,id',
            'fechaacceso' => 'required|date',
            'id_cirujano' => 'nullable|exists:cirujanos,id',
            'observaciones' => 'nullable|string',
        ]);

        AccesoVascular::create([
            'id_paciente' => $paciente->id,
            'id_tipoacceso' => $request->id_tipoacceso,
            'id_cirujano' => $request->id_cirujano,
            'fechaacceso' => $request->fechaacceso,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('pacientes.show', $paciente)
        ->with('success', 'Acceso vascular creado exitosamente.')
        ->with('show_tab', 'accesos');
    }
}