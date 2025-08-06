<?php

namespace App\Http\Controllers;

use App\Models\AnalisisMensual;

$analisis = AnalisisMensual::create([
    'id_paciente' => $id,
    'fechaanalisis' => $request->fechaanalisis,
    'protocolo' => $request->protocolo,
    'hemoglobina' => $request->hemoglobina,
    'hematocrito' => $request->hematocrito,
    'rto_blancos' => $request->rto_blancos,
    'rto_rojos' => $request->rto_rojos,
    'rto_plaquetas' => $request->rto_plaquetas,
    'creatinina' => $request->creatinina,
    'uremia_pre' => $request->uremia_pre,
    'uremia_post' => $request->uremia_post,
    'urea_creatinina' => $request->urea_creatinina,
    'rpu' => $request->rpu,
    'ktv_daugiras' => $request->ktv_daugiras,
    'ktv_basile' => $request->ktv_basile,
    'tac_urea' => $request->tac_urea,
    'sodio' => $request->sodio,
    'potasio' => $request->potasio,
    'calcemia' => $request->calcemia,
    'fosfatemia' => $request->fosfatemia,
    'gpt' => $request->gpt,
    'got' => $request->got,
    'fosfatasa_alcalina' => $request->fosfatasa_alcalina,
    'pcr' => $request->pcr,
]);
use App\Models\Paciente;
use Illuminate\Http\Request;

class AnalisisMensualController extends Controller
{
    public function store(Request $request, $pacienteId)
    {
        $request->validate([
            'fechaanalisis' => 'required|date',
            'protocolo' => 'nullable|string|max:100',
            'hemoglobina' => 'nullable|numeric',
            'hematocrito' => 'nullable|numeric',
            'rto_blancos' => 'nullable|numeric',
            'rto_rojos' => 'nullable|numeric',
            'rto_plaquetas' => 'nullable|numeric',
            'creatinina' => 'nullable|numeric',
            'uremia_pre' => 'nullable|numeric',
            'uremia_post' => 'nullable|numeric',
            'urea_creatinina' => 'nullable|numeric',
            'rpu' => 'nullable|numeric',
            'ktv_daugiras' => 'nullable|numeric',
            'ktv_basile' => 'nullable|numeric',
            'tac_urea' => 'nullable|numeric',
            'sodio' => 'nullable|numeric',
            'potasio' => 'nullable|numeric',
            'calcemia' => 'nullable|numeric',
            'fosfatemia' => 'nullable|numeric',
            'gpt' => 'nullable|numeric',
            'got' => 'nullable|numeric',
            'fosfatasa_alcalina' => 'nullable|numeric',
            'pcr' => 'nullable|numeric',
        ]);

        $paciente = Paciente::findOrFail($pacienteId);

        AnalisisMensual::create([
            'id_paciente' => $paciente->id,
            'fechaanalisis' => $request->fechaanalisis,
            'hemoglobina' => $request->hemoglobina,
            'hematocrito' => $request->hematocrito,
            'creatinina' => $request->creatinina,
            'uremia_pre' => $request->uremia_pre,
            'uremia_post' => $request->uremia_post,
            'ktv_daugiras' => $request->ktv_daugiras,
            'rpu' => $request->rpu,
            'sodio' => $request->sodio,
            'potasio' => $request->potasio,
            'calcemia' => $request->calcemia,
            'pcr' => $request->pcr,
      'urr' => $request->urr,
      'urr' => $request->urr,
        ]);

        return redirect()->route('pacientes.show', $paciente->id)
            ->with('success', 'Análisis mensual registrado correctamente.');
    }
}
