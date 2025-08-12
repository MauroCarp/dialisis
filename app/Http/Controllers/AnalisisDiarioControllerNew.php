<?php

namespace App\Http\Controllers;

use App\Models\AnalisisDiario;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalisisDiarioController extends Controller
{
    /**
     * Guardar datos PRE-diálisis (primera parte)
     */
    public function storePreDialisis(Request $request, $pacienteId)
    {
        $request->validate([
            'fechaanalisis' => 'required|date',
            'id_tipofiltro' => 'required|exists:tiposfiltros,id',
            'pesopre' => 'required|numeric|min:0',
            'taspre' => 'required|integer|min:0',
            'tadpre' => 'required|integer|min:0',
            'relpesosecopesopre' => 'required|numeric|min:0',
            'interdialitico' => 'required|numeric|min:0',
        ]);

        $paciente = Paciente::findOrFail($pacienteId);

        // Verificar si ya existe un registro para esta fecha
        $existingAnalysis = AnalisisDiario::where('id_paciente', $paciente->id)
            ->whereDate('fechaanalisis', $request->fechaanalisis)
            ->first();

        if ($existingAnalysis) {
            return redirect()->route('pacientes.show', $paciente->id)
                ->with('error', 'Ya existe un análisis diario para esta fecha.');
        }

        AnalisisDiario::create([
            'id_paciente' => $paciente->id,
            'fechaanalisis' => $request->fechaanalisis,
            'id_tipofiltro' => $request->id_tipofiltro,
            'pesopre' => $request->pesopre,
            'taspre' => $request->taspre,
            'tadpre' => $request->tadpre,
            'relpesosecopesopre' => $request->relpesosecopesopre,
            'interdialitico' => $request->interdialitico,
            'estado' => 'pre_dialisis'
        ]);

        return redirect()->route('pacientes.show', $paciente->id)
            ->with('success', 'Datos PRE-diálisis registrados correctamente. Recuerde completar los datos POST-diálisis más tarde.')
            ->with('show_tab', 'analisis');
    }

    /**
     * Completar datos POST-diálisis (segunda parte)
     */
    public function completarPostDialisis(Request $request, $analisisId)
    {
        $request->validate([
            'pesopost' => 'required|numeric|min:0',
            'taspos' => 'required|integer|min:0',
            'tadpos' => 'required|integer|min:0',
        ]);

        $analisis = AnalisisDiario::findOrFail($analisisId);

        // Verificar que el análisis esté en estado pre_dialisis
        if ($analisis->estado !== 'pre_dialisis') {
            return redirect()->route('pacientes.show', $analisis->id_paciente)
                ->with('error', 'Este análisis ya ha sido completado.');
        }

        $analisis->update([
            'pesopost' => $request->pesopost,
            'taspos' => $request->taspos,
            'tadpos' => $request->tadpos,
            'estado' => 'completo'
        ]);

        return redirect()->route('pacientes.show', $analisis->id_paciente)
            ->with('success', 'Análisis diario completado correctamente.')
            ->with('show_tab', 'analisis');
    }

    /**
     * Obtener análisis pendientes de completar para un paciente
     */
    public function getPendientes($pacienteId)
    {
        $pendientes = AnalisisDiario::where('id_paciente', $pacienteId)
            ->where('estado', 'pre_dialisis')
            ->orderBy('fechaanalisis', 'desc')
            ->get();

        return response()->json($pendientes);
    }

    /**
     * Método original para compatibilidad (opcional)
     */
    public function store(Request $request, $pacienteId)
    {
        // Redirigir al método pre-dialisis
        return $this->storePreDialisis($request, $pacienteId);
    }
}
