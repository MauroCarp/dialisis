<?php

namespace App\Http\Controllers;

use App\Models\AnalisisTrimestral;
use App\Models\Paciente;
use Illuminate\Http\Request;

class AnalisisTrimestralController extends Controller
{
    public function store(Request $request, $pacienteId)
    {
        $request->validate([
            'fechaanalisis' => 'required|date',
            'protocolo' => 'nullable|string|max:100',
            'albumina' => 'nullable|numeric',
            'colesterol' => 'nullable|numeric',
            'trigliseridos' => 'nullable|numeric',
        ]);

        $paciente = Paciente::findOrFail($pacienteId);

        AnalisisTrimestral::create([
            'id_paciente' => $paciente->id,
            'fechaanalisis' => $request->fechaanalisis,
            'protocolo' => $request->protocolo,
            'albumina' => $request->albumina,
            'colesterol' => $request->colesterol,
            'trigliseridos' => $request->trigliseridos,
        ]);

        return redirect()->route('pacientes.show', $paciente->id)
            ->with('success', 'Análisis trimestral registrado correctamente.')
            ->with('show_tab', 'analisis')
            ->with('analisis_tab', 'trimestrales');
    }

    public function edit($id)
    {
        $analisis = AnalisisTrimestral::findOrFail($id);
        return response()->json($analisis);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fechaanalisis' => 'nullable|date',
            'protocolo' => 'nullable|string|max:100',
            'linfocitos' => 'nullable|numeric',
            'albumina' => 'nullable|numeric',
            'colesterol' => 'nullable|numeric',
        ]);

        $analisis = AnalisisTrimestral::findOrFail($id);
        $analisis->update($request->all());

        return redirect()->route('pacientes.show', $analisis->id_paciente)
            ->with('success', 'Análisis trimestral actualizado correctamente.')
            ->with('show_tab', 'analisis')
            ->with('analisis_tab', 'trimestrales');
    }

    public function destroy($id)
    {
        $analisis = AnalisisTrimestral::findOrFail($id);
        $pacienteId = $analisis->id_paciente;
        $analisis->delete();

        return redirect()->route('pacientes.show', $pacienteId)
            ->with('success', 'Análisis trimestral eliminado correctamente.')
            ->with('show_tab', 'analisis')
            ->with('analisis_tab', 'trimestrales');
    }
}
