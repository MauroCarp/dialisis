<?php

namespace App\Http\Controllers;

use App\Models\Dosis;
use App\Models\VacunaPaciente;
use Illuminate\Http\Request;

class DosisController extends Controller
{
    public function store(Request $request, $vacunaPacienteId)
    {
        $request->validate([
            'fechadosis' => 'required|date',
            'numero' => 'required|integer|min:1',
            'cantidad' => 'nullable|numeric',
        ]);

        $vacunaPaciente = VacunaPaciente::findOrFail($vacunaPacienteId);

        Dosis::create([
            'id_vacunapaciente' => $vacunaPaciente->id,
            'fechadosis' => $request->fechadosis,
            'numero' => $request->numero,
            'cantidad' => $request->cantidad,
        ]);

        return redirect()->route('pacientes.show', $vacunaPaciente->id_paciente)
            ->with('success', 'Dosis registrada correctamente.')
            ->with('show_tab', 'vacunas');
    }
}
