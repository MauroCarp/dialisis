<?php

namespace App\Http\Controllers;

use App\Models\AnalisisDiario;
use App\Models\Paciente;
use App\Models\TipoFiltro;
use App\Models\TipoSesion;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class AnalisisDiarioEtapasController extends Controller
{
    /**
     * Almacena la primera etapa del análisis diario (pre-diálisis)
     */
    public function storePreDialisis(Request $request, $pacienteId): RedirectResponse
    {
        $request->validate([
            'fechaanalisis' => 'required|date',
            'pesopre' => 'required|numeric|min:0|max:500',
            'taspre' => 'required|numeric|min:0|max:300',
            'tadpre' => 'required|numeric|min:0|max:200',
            'id_tipofiltro' => 'required|exists:tiposfiltros,id',
            'relpesosecopesopre' => 'nullable|numeric|min:0|max:100',
            'interdialitico' => 'nullable|numeric|min:0|max:10'
        ]);

        // Verificar si ya existe un análisis para esta fecha
        $analisisExistente = AnalisisDiario::where('id_paciente', $pacienteId)
            ->whereDate('fechaanalisis', $request->fechaanalisis)
            ->first();

        if ($analisisExistente) {
            // Si existe, actualizar solo los campos pre-diálisis
            $analisisExistente->update([
                'pesopre' => $request->pesopre,
                'taspre' => $request->taspre,
                'tadpre' => $request->tadpre,
                'id_tipofiltro' => $request->id_tipofiltro,
                'relpesosecopesopre' => $request->relpesosecopesopre,
                'interdialitico' => $request->interdialitico,
                'estado' => $analisisExistente->estaCompleto() ? 'completo' : 'pre_dialisis'
            ]);
            
            $mensaje = 'Datos pre-diálisis actualizados correctamente';
        } else {
            // Si no existe, crear nuevo registro
            AnalisisDiario::create([
                'fechaanalisis' => $request->fechaanalisis,
                'id_paciente' => $pacienteId,
                'pesopre' => $request->pesopre,
                'taspre' => $request->taspre,
                'tadpre' => $request->tadpre,
                'id_tipofiltro' => $request->id_tipofiltro,
                'relpesosecopesopre' => $request->relpesosecopesopre,
                'interdialitico' => $request->interdialitico,
                'estado' => 'pre_dialisis'
            ]);
            
            $mensaje = 'Datos pre-diálisis registrados correctamente';
        }

        return redirect()->back()
            ->with('success', $mensaje)
            ->with('show_tab', 'analisis');
    }

    /**
     * Almacena la segunda etapa del análisis diario (post-diálisis)
     */
    public function storePostDialisis(Request $request, $pacienteId): RedirectResponse
    {
        $request->validate([
            'fechaanalisis' => 'required|date',
            'pesopost' => 'required|numeric|min:0|max:500',
            'taspos' => 'required|numeric|min:0|max:300',
            'tadpos' => 'required|numeric|min:0|max:200',
            'id_tiposesion' => 'nullable|exists:tipossesiones,id',
        ]);

        // Buscar el análisis existente para esta fecha
        $analisis = AnalisisDiario::where('id_paciente', $pacienteId)
            ->whereDate('fechaanalisis', $request->fechaanalisis)
            ->first();

        if (!$analisis) {
            return redirect()->back()
                ->withErrors(['fechaanalisis' => 'No se encontraron datos pre-diálisis para esta fecha. Debe cargar primero los datos pre-diálisis.'])
                ->with('show_tab', 'analisis');
        }

        // Actualizar con los datos post-diálisis
        $analisis->update([
            'pesopost' => $request->pesopost,
            'taspos' => $request->taspos,
            'tadpos' => $request->tadpos,
            'id_tiposesion' => $request->id_tiposesion,
            'estado' => 'completo'
        ]);

        return redirect()->back()
            ->with('success', 'Análisis diario completado correctamente')
            ->with('show_tab', 'analisis');
    }

    /**
     * Obtiene los análisis incompletos para mostrar en la vista
     */
    public function getAnalisisIncompletos($pacienteId)
    {
        return AnalisisDiario::where('id_paciente', $pacienteId)
            ->where('estado', '!=', 'completo')
            ->with(['tipoFiltro', 'tipoSesion'])
            ->orderBy('fechaanalisis', 'desc')
            ->get();
    }

    /**
     * Obtiene un análisis específico por fecha para completar
     */
    public function getAnalisisPorFecha(Request $request, $pacienteId)
    {
        $request->validate([
            'fecha' => 'required|date'
        ]);

        $analisis = AnalisisDiario::where('id_paciente', $pacienteId)
            ->whereDate('fechaanalisis', $request->fecha)
            ->with(['tipoFiltro', 'tipoSesion'])
            ->first();

        if (!$analisis) {
            return response()->json(['error' => 'No se encontró análisis para esta fecha'], 404);
        }

        return response()->json([
            'analisis' => $analisis,
            'puede_completar' => $analisis->esPreDialisis()
        ]);
    }

    /**
     * Editar análisis diario (obtener datos para edición)
     */
    public function edit($id)
    {
        try {
            $analisis = AnalisisDiario::with(['tipoFiltro', 'tipoSesion', 'paciente'])->findOrFail($id);
            
            // Log para debug
            \Log::info('Análisis encontrado:', ['id' => $id, 'analisis' => $analisis->toArray()]);
            
            return response()->json($analisis);
        } catch (\Exception $e) {
            \Log::error('Error al cargar análisis:', ['id' => $id, 'error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los datos del análisis: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar análisis diario
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'fechaanalisis' => 'required|date',
            'pesopre' => 'required|numeric|min:0|max:500',
            'taspre' => 'required|numeric|min:0|max:300',
            'tadpre' => 'required|numeric|min:0|max:200',
            'id_tipofiltro' => 'required|exists:tiposfiltros,id',
            'relpesosecopesopre' => 'nullable|numeric|min:0|max:100',
            'interdialitico' => 'nullable|numeric|min:0|max:100',
            // Datos POST (opcionales para análisis completos)
            'pesopost' => 'nullable|numeric|min:0|max:500',
            'taspos' => 'nullable|numeric|min:0|max:300',
            'tadpos' => 'nullable|numeric|min:0|max:200',
            'id_tiposesion' => 'nullable|exists:tipossesiones,id',
        ]);

        try {
            $analisis = AnalisisDiario::findOrFail($id);
            $pacienteId = $analisis->id_paciente;

            // Verificar si ya existe otro análisis para la nueva fecha (excepto el actual)
            $existeOtroAnalisis = AnalisisDiario::where('id_paciente', $pacienteId)
                ->whereDate('fechaanalisis', $request->fechaanalisis)
                ->where('id', '!=', $id)
                ->exists();

            if ($existeOtroAnalisis) {
                return redirect()->back()
                    ->withErrors(['fechaanalisis' => 'Ya existe un análisis para esa fecha'])
                    ->with('show_tab', 'analisis');
            }

            // Preparar datos para actualizar
            $datosUpdate = [
                'fechaanalisis' => $request->fechaanalisis,
                'pesopre' => $request->pesopre,
                'taspre' => $request->taspre,
                'tadpre' => $request->tadpre,
                'id_tipofiltro' => $request->id_tipofiltro,
                'relpesosecopesopre' => $request->relpesosecopesopre,
                'interdialitico' => $request->interdialitico,
            ];

            // Si se incluyen datos POST, también actualizarlos
            if ($request->filled('pesopost') || $request->filled('taspos') || 
                $request->filled('tadpos')) {
                $datosUpdate = array_merge($datosUpdate, [
                    'pesopost' => $request->pesopost,
                    'taspos' => $request->taspos,
                    'tadpos' => $request->tadpos,
                    'id_tiposesion' => $request->id_tiposesion,
                    'estado' => 'completo'
                ]);
            }

            $analisis->update($datosUpdate);

            // Si es una petición AJAX, responder con JSON
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Análisis diario actualizado correctamente'
                ]);
            }

            return redirect()->route('pacientes.show', $pacienteId)
                ->with('success', 'Análisis diario actualizado correctamente')
                ->with('show_tab', 'analisis');

        } catch (\Exception $e) {
            // Si es una petición AJAX, responder con JSON de error
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el análisis: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error al actualizar el análisis: ' . $e->getMessage())
                ->with('show_tab', 'analisis');
        }
    }

    /**
     * Eliminar análisis diario
     */
    public function destroy($id)
    {
        try {
            $analisis = AnalisisDiario::findOrFail($id);
            $pacienteId = $analisis->id_paciente;
            
            $analisis->delete();

            // Si es una petición AJAX, responder con JSON
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Análisis diario eliminado correctamente'
                ]);
            }

            return redirect()->route('pacientes.show', $pacienteId)
                ->with('success', 'Análisis diario eliminado correctamente')
                ->with('show_tab', 'analisis');

        } catch (\Exception $e) {
            // Si es una petición AJAX, responder con JSON de error
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el análisis: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error al eliminar el análisis: ' . $e->getMessage())
                ->with('show_tab', 'analisis');
        }
    }
}
