<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PacienteController extends Controller
{
    /**
     * Show the form for editing the specified paciente.
     */
    public function edit(Paciente $paciente): View
    {
        return view('pacientes.edit', compact('paciente'));
    }

    /**
     * Update the specified paciente in storage.
     */
    public function update(Request $request, Paciente $paciente)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dnicuitcuil' => 'nullable|string|max:20',
            'fechanacimiento' => 'nullable|date',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:500',
            'id_localidad' => 'nullable|exists:localidades,id',
            'pesoseco' => 'nullable|numeric|min:0',
            'talla' => 'nullable|numeric|min:0',
            'gruposanguineo' => 'nullable|string|max:10',
            'fumador' => 'boolean',
            'insulinodependiente' => 'boolean',
        ]);

        $paciente->update($validated);

        return redirect()->back()->with('success', 'Paciente actualizado correctamente.');
    }
}
