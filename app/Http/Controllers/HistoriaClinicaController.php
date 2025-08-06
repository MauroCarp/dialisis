<?php

namespace App\Http\Controllers;

use App\Models\HistoriaClinica;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class HistoriaClinicaController extends Controller
{
    public function create($id)
    {
        $paciente = Paciente::findOrFail($id);
        return view('historias-clinicas.create', compact('paciente'));
    }

    public function store(Request $request, $id)
    {
        $paciente = Paciente::findOrFail($id);
        
        $validated = $request->validate([
            'fechahistoriaclinica' => 'required|date',
            'observaciones' => 'nullable|string'
        ]);

        $validated['id_paciente'] = $paciente->id;

        HistoriaClinica::create($validated);

        return redirect()
            ->route('pacientes.show', $paciente->id)
            ->with('success', 'Historia clínica creada exitosamente.');
    }

    public function download($id)
    {
        $historia = HistoriaClinica::with('paciente')->findOrFail($id);
        $paciente = $historia->paciente;

        // Generar PDF
        $pdf = Pdf::loadView('historias-clinicas.pdf', compact('historia', 'paciente'))
            ->setPaper('a4', 'portrait');

        $filename = 'Historia_Clinica_' . $paciente->apellido . '_' . $paciente->nombre . '_' . 
                   $historia->fechahistoriaclinica->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}
