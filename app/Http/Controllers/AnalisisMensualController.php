<?php

namespace App\Http\Controllers;

use App\Models\AnalisisMensual;
use App\Models\AnalisisDiario;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
            'sodio' => 'nullable|numeric',
            'potasio' => 'nullable|numeric',
            'calcemia' => 'nullable|numeric',
            'fosfatemia' => 'nullable|numeric',
            'gpt' => 'nullable|numeric',
            'got' => 'nullable|numeric',
            'fosfatasa_alcalina' => 'nullable|numeric',
        ]);

        $paciente = Paciente::findOrFail($pacienteId);

        // Obtener la fecha del análisis mensual
        $fechaAnalisis = Carbon::parse($request->fechaanalisis);
        
        // Calcular el primer y último día del mes
        $mes = $fechaAnalisis->month;
        $anio = $fechaAnalisis->year;
        
        // Obtener los análisis diarios del paciente en ese mes
        $analisisDiariosMes = AnalisisDiario::where('id_paciente', $paciente->id)
            ->whereMonth('fechaanalisis', $mes)
            ->whereYear('fechaanalisis', $anio)
            ->get();
        
        // Calcular los promedios de peso
        $peso_pre_promedio = 0;
        $peso_post_promedio = 0;
        $calculoPesosProm = 0;
        
        if ($analisisDiariosMes->count() > 0) {
            $peso_pre_promedio = $analisisDiariosMes->avg('pesopre');
            $peso_post_promedio = $analisisDiariosMes->avg('pesopost');
            
            // Evitar división por cero
            if ($peso_post_promedio > 0) {
                $calculoPesosProm = ($peso_pre_promedio - $peso_post_promedio) / $peso_post_promedio;
            }
        }

        $rpu = ($request->uremia_pre - $request->uremia_post) / $request->uremia_pre * 100;

        $logNeg = -log(($request->uremia_pre / $request->uremia_post) - 0.008 * 4);
        $calculoUremico = 4 - 3.5 * $request->uremia_post / $request->uremia_pre;

        $analisis = AnalisisMensual::create([
            'id_paciente' => $pacienteId,
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
            'urea_creatinina' => ($request->uremia_pre / $request->creatinina) * 10,
            'rpu' => $rpu,
            'ktv_daugiras' => ($logNeg + ($calculoUremico) * ($calculoPesosProm)),
            'ktv_basile' => ($rpu * 0.023) - 0.284,
            'tac_urea' => ($request->uremia_pre + $request->uremia_post) / 2,
            'sodio' => $request->sodio,
            'potasio' => $request->potasio,
            'calcemia' => $request->calcemia,
            'fosfatemia' => $request->fosfatemia,
            'gpt' => $request->gpt,
            'got' => $request->got,
            'fosfatasa_alcalina' => $request->fosfatasa_alcalina,
            'pcr' => ($request->uremia_pre / 0.02143) / (25.8 + 1.15 * ($logNeg + ($calculoUremico) * $calculoPesosProm) + 56.4 / ($logNeg + ($calculoUremico) * ($calculoPesosProm))) + 0.168,
        ]);

        return redirect()->route('pacientes.show', $paciente->id)
            ->with('success', 'Análisis mensual registrado correctamente.')
            ->with('show_tab', 'analisis')
            ->with('analisis_tab', 'mensuales');
    }

    public function edit($id)
    {
        $analisis = AnalisisMensual::findOrFail($id);
        return response()->json($analisis);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fechaanalisis' => 'nullable|date',
            'protocolo' => 'nullable|string|max:100',
            'hemoglobina' => 'nullable|numeric',
            'hematocrito' => 'nullable|numeric',
            'globulos_blancos' => 'nullable|numeric',
            'plaquetas' => 'nullable|numeric',
            'transferrina' => 'nullable|numeric',
            'ferritina' => 'nullable|numeric',
            'urea' => 'nullable|numeric',
            'creatinina' => 'nullable|numeric',
            'acido_urico' => 'nullable|numeric',
            'clearance_creatinina' => 'nullable|numeric',
            'colesterol_total' => 'nullable|numeric',
            'hdl' => 'nullable|numeric',
            'ldl' => 'nullable|numeric',
            'trigliceridos' => 'nullable|numeric',
            'glucemia' => 'nullable|numeric',
            'albumina' => 'nullable|numeric',
            'proteinas_totales' => 'nullable|numeric',
            'sodio' => 'nullable|numeric',
            'potasio' => 'nullable|numeric',
            'calcemia' => 'nullable|numeric',
            'fosfatemia' => 'nullable|numeric',
            'gpt' => 'nullable|numeric',
            'got' => 'nullable|numeric',
            'fosfatasa_alcalina' => 'nullable|numeric',
            'pcr' => 'nullable|numeric',
        ]);

        $analisis = AnalisisMensual::findOrFail($id);
        
        // Preparar los datos para actualizar, excluyendo fechaanalisis si viene vacío
        $updateData = $request->all();
        
        // Si fechaanalisis viene vacío o null, removerlo de los datos de actualización
        // para preservar el valor original en la base de datos
        if (empty($request->fechaanalisis)) {
            unset($updateData['fechaanalisis']);
        }
        
        $analisis->update($updateData);

        return redirect()->route('pacientes.show', $analisis->id_paciente)
            ->with('success', 'Análisis mensual actualizado correctamente.')
            ->with('show_tab', 'analisis')
            ->with('analisis_tab', 'mensuales');
    }

    public function destroy($id)
    {
        $analisis = AnalisisMensual::findOrFail($id);
        $pacienteId = $analisis->id_paciente;
        $analisis->delete();

        return redirect()->route('pacientes.show', $pacienteId)
            ->with('success', 'Análisis mensual eliminado correctamente.')
            ->with('show_tab', 'analisis')
            ->with('analisis_tab', 'mensuales');
    }
}
