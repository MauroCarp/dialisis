<?php

namespace App\Http\Controllers;

use App\Models\HistoriaClinicaConsultorio;
use App\Models\PacienteConsultorio;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class HistoriaClinicaConsultorioController extends Controller
{
    public function create($id)
    {
        $paciente = PacienteConsultorio::findOrFail($id);
        return view('historias-clinicas-consultorio.create', compact('paciente'));
    }

    public function store(Request $request, $id)
    {
        $paciente = PacienteConsultorio::findOrFail($id);
        
        $validated = $request->validate([
            'fechahistoriaclinica' => 'required|date',
            'observaciones' => 'nullable|string'
        ]);

        $validated['id_paciente'] = $paciente->id;

        HistoriaClinicaConsultorio::create($validated);

        return redirect()
            ->route('pacientes.show', ['paciente' => $paciente->id, 'tipo' => 'consultorio'])
            ->with('success', 'Historia clínica de consultorio creada exitosamente.')
            ->with('show_tab', 'historias');
    }

    public function download($id)
    {
        $historia = HistoriaClinicaConsultorio::with('pacienteConsultorio')->findOrFail($id);
        $paciente = $historia->pacienteConsultorio;

        // Generar PDF
        $pdf = Pdf::loadView('historias-clinicas.pdf', compact('historia', 'paciente'))
            ->setPaper('a4', 'portrait');

        $filename = 'Historia_Clinica_Consultorio_' . $paciente->apellido . '_' . $paciente->nombre . '_' . 
                   $historia->fechahistoriaclinica->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    public function destroy($id)
    {
        $historia = HistoriaClinicaConsultorio::with('pacienteConsultorio')->findOrFail($id);
        $pacienteId = $historia->pacienteConsultorio->id;
        
        $historia->delete();

        return redirect()
            ->route('pacientes.show', ['paciente' => $pacienteId, 'tipo' => 'consultorio'])
            ->with('success', 'Historia clínica de consultorio eliminada exitosamente.')
            ->with('show_tab', 'historias');
    }
}
