<?php

namespace App\Http\Controllers;

use App\Models\AnalisisSemestral;
use App\Models\Paciente;
use Illuminate\Http\Request;

class AnalisisSemestralController extends Controller
{
    public function store(Request $request, $pacienteId)
    {
        try {
            // Debug: Log inicial
            \Log::info('=== INICIO ANÁLISIS SEMESTRAL ===');
            \Log::info('Paciente ID:', ['paciente_id' => $pacienteId]);
            \Log::info('Request data:', $request->all());

            $request->validate([
                'fechaanalisis' => 'required|date',
                'protocolo' => 'nullable|string|max:100',
                'hbsag' => 'nullable|in:0,1',
                'antihbsag' => 'nullable|in:0,1',
                'valorantihbsag' => 'nullable|numeric',
                'antihcv' => 'nullable|in:0,1',
                'antihiv' => 'nullable|in:0,1',
                'anticore' => 'nullable|in:0,1',
                'pth' => 'nullable|numeric',
                'ferritina' => 'nullable|numeric',
                'ferremia' => 'nullable|numeric',
            ]);

            \Log::info('Validación pasada correctamente');

            $paciente = Paciente::findOrFail($pacienteId);
            \Log::info('Paciente encontrado:', ['paciente' => $paciente->nombre . ' ' . $paciente->apellido]);

            // Debug: Log de los datos recibidos
            \Log::info('Datos del formulario semestral:', [
                'paciente_id' => $paciente->id,
                'request_data' => $request->all()
            ]);

            $analisis = AnalisisSemestral::create([
                'id_paciente' => $paciente->id,
                'fechaanalisis' => $request->fechaanalisis,
                'protocolo' => $request->protocolo,
                'hbsag' => $request->input('hbsag') == '1',
                'antihbsag' => $request->input('antihbsag') == '1',
                'valorantihbsag' => $request->valorantihbsag,
                'antihcv' => $request->input('antihcv') == '1',
                'antihiv' => $request->input('antihiv') == '1',
                'anticore' => $request->input('anticore') == '1',
                'pth' => $request->pth,
                'ferritina' => $request->ferritina,
                'ferremia' => $request->ferremia,
            ]);

            // Debug: Log del análisis creado
            \Log::info('Análisis semestral creado:', [
                'analisis_id' => $analisis->id,
                'analisis_data' => $analisis->toArray()
            ]);

            \Log::info('=== REDIRIGIENDO ===');

            return redirect()->route('pacientes.show', $paciente->id)
                ->with('success', 'Análisis semestral registrado correctamente.')
                ->with('show_tab', 'analisis')
                ->with('analisis_tab', 'semestrales');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Error de validación:', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error general en análisis semestral:', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->all()
            ]);
            throw $e;
        }
    }

    public function edit($id)
    {
        $analisis = AnalisisSemestral::findOrFail($id);
        \Log::info('Datos del análisis semestral para edición:', $analisis->toArray());
        return response()->json($analisis);
    }

    public function update(Request $request, $id)
    {
        try {
            \Log::info('Iniciando actualización de análisis semestral:', [
                'id' => $id,
                'request_data' => $request->all()
            ]);

            $request->validate([
                'fechaanalisis' => 'nullable|date',
                'protocolo' => 'nullable|string|max:100',
                'hbsag' => 'nullable|in:0,1',
                'antihbsag' => 'nullable|in:0,1',
                'valorantihbsag' => 'nullable|numeric',
                'antihcv' => 'nullable|in:0,1',
                'antihiv' => 'nullable|in:0,1',
                'anticore' => 'nullable|in:0,1',
                'pth' => 'nullable|numeric',
                'ferritina' => 'nullable|numeric',
                'ferremia' => 'nullable|numeric',
            ]);

            \Log::info('Validación exitosa');

            $analisis = AnalisisSemestral::findOrFail($id);
            $pacienteId = $analisis->id_paciente;

            \Log::info('Análisis encontrado:', [
                'analisis_id' => $analisis->id,
                'paciente_id' => $pacienteId
            ]);

            $updateData = [
                'fechaanalisis' => $request->fechaanalisis ?: $analisis->fechaanalisis,
                'protocolo' => $request->protocolo,
                'hbsag' => $request->input('hbsag') == '1',
                'antihbsag' => $request->input('antihbsag') == '1',
                'valorantihbsag' => $request->valorantihbsag,
                'antihcv' => $request->input('antihcv') == '1',
                'antihiv' => $request->input('antihiv') == '1',
                'anticore' => $request->input('anticore') == '1',
                'pth' => $request->pth,
                'ferritina' => $request->ferritina,
                'ferremia' => $request->ferremia,
            ];

            \Log::info('Datos a actualizar:', $updateData);

            $analisis->update($updateData);

            \Log::info('Análisis actualizado exitosamente');

            return redirect()->route('pacientes.show', $pacienteId)
                ->with('success', 'Análisis semestral actualizado exitosamente.')
                ->with('show_tab', 'analisis')
                ->with('analisis_tab', 'semestrales');

        } catch (\Exception $e) {
            \Log::error('Error al actualizar análisis semestral:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return back()->with('error', 'Error al actualizar el análisis semestral: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $analisis = AnalisisSemestral::findOrFail($id);
        $pacienteId = $analisis->id_paciente;
        
        $analisis->delete();

        return redirect()->route('pacientes.show', $pacienteId)
            ->with('success', 'Análisis semestral eliminado exitosamente.')
            ->with('show_tab', 'analisis')
            ->with('analisis_tab', 'semestrales');
    }
}
