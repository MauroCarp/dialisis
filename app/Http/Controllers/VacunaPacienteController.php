<?php

namespace App\Http\Controllers;

use App\Models\VacunaPaciente;
use App\Models\Paciente;
use App\Models\PacienteConsultorio;
use Illuminate\Http\Request;

class VacunaPacienteController extends Controller
{
    public function store(Request $request, $pacienteId)
    {
        $request->validate([
            'fechavacuna' => 'required|date',
            'id_vacuna' => 'required|exists:vacunas,id',
            'observaciones' => 'nullable|string',
        ]);

        VacunaPaciente::create([
            'id_paciente' => $pacienteId,
            'fechavacuna' => $request->fechavacuna,
            'id_vacuna' => $request->id_vacuna,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('pacientes.show', $pacienteId)
            ->with('success', 'Vacuna registrada correctamente.');
    }
}
