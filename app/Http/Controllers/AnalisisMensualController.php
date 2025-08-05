<?php

namespace App\Http\Controllers;

use App\Models\AnalisisMensual;
use App\Models\Paciente;
use Illuminate\Http\Request;

class AnalisisMensualController extends Controller
{
    public function store(Request $request, $pacienteId)
    {
        $request->validate([
            'fechaanalisis' => 'required|date',
            'urea' => 'nullable|numeric',
            'creatinina' => 'nullable|numeric',
            'hemoglobina' => 'nullable|numeric',
            'hematocrito' => 'nullable|numeric',
            'kt_v' => 'nullable|numeric',
            'urr' => 'nullable|numeric',
        ]);

        $paciente = Paciente::findOrFail($pacienteId);

        AnalisisMensual::create([
            'id_paciente' => $paciente->id,
            'fechaanalisis' => $request->fechaanalisis,
            'urea' => $request->urea,
            'creatinina' => $request->creatinina,
            'hemoglobina' => $request->hemoglobina,
            'hematocrito' => $request->hematocrito,
            'kt_v' => $request->kt_v,
            'urr' => $request->urr,
        ]);

        return redirect()->route('pacientes.show', $paciente->id)
            ->with('success', 'Análisis mensual registrado correctamente.');
    }
}
