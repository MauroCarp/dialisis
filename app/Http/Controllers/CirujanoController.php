<?php

namespace App\Http\Controllers;

use App\Models\Cirujano;
use Illuminate\Http\Request;

class CirujanoController extends Controller
{
    /**
     * Store a newly created cirujano in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'nullable|string|max:255',
            'matricula' => 'nullable|string|max:50',
        ]);

        $cirujano = Cirujano::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'matricula' => $request->matricula,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cirujano creado exitosamente',
            'cirujano' => $cirujano
        ]);
    }
}
