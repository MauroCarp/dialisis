<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $paciente->nombre }} {{ $paciente->apellido }} - Detalles del Paciente</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            {{ $paciente->nombre }} {{ $paciente->apellido }}
                            @if(isset($esPacienteConsultorio) && $esPacienteConsultorio)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                    Consultorio
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2">
                                    Diálisis
                                </span>
                            @endif
                        </h1>
                        <p class="text-gray-600 mt-1">
                            DNI/CUIL/CUIT: {{ $paciente->dnicuitcuil ?? 'No especificado' }}
                        </p>
                        @if($paciente->nroalta)
                            <p class="text-gray-600">
                                Nro. Alta: {{ $paciente->nroalta }}
                            </p>
                        @endif
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('pacientes.edit', $paciente) }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Editar
                        </a>
                        <a href="/admin" 
                           style="background-color: #009999;" class="hover:bg-primary-700 text-white font-bold py-2 px-4 rounded">
                            Volver al Inicio
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Información Personal -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Información Personal</h2>
                    <div class="space-y-3">
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Nombre:</span>
                                <p class="text-gray-900">{{ $paciente->nombre }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Apellido:</span>
                                <p class="text-gray-900">{{ $paciente->apellido }}</p>
                            </div>
                 

                            @if($paciente->fechanacimiento)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Fecha de Nacimiento:</span>
                                    <p class="text-gray-900">
                                        {{ $paciente->fechanacimiento->format('d/m/Y') }}
                                        ({{ $paciente->fechanacimiento->age }} años)
                                    </p>
                                </div>
                            @endif

                            @if($paciente->telefono)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Teléfono:</span>
                                    <p class="text-gray-900">{{ $paciente->telefono }}</p>
                                </div>
                            @endif

                            @if($paciente->email)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Email:</span>
                                    <p class="text-gray-900">{{ $paciente->email }}</p>
                                </div>
                            @endif

                            @if($paciente->direccion)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Dirección:</span>
                                    <p class="text-gray-900">{{ $paciente->direccion }}</p>
                                </div>
                            @endif

                            @if($paciente->localidad)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Localidad:</span>
                                    <p class="text-gray-900">
                                        {{ $paciente->localidad->nombre }}
                                        @if($paciente->localidad->provincia)
                                            , {{ $paciente->localidad->provincia->nombre }}
                                        @endif
                                    </p>
                                </div>
                            @endif

                            @if($paciente->fechaingreso)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Fecha de Ingreso:</span>
                                    <p class="text-gray-900">{{ $paciente->fechaingreso->format('d/m/Y') }}</p>
                                </div>
                            @endif

                        </div>

                    </div>
                </div>

                <!-- Información Médica -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Información Médica</h2>
                    
                    <div class="space-y-3">
                        <div class="grid grid-cols-3 gap-4">
                            @if($paciente->pesoseco)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Peso Seco:</span>
                                    <p class="text-gray-900">{{ $paciente->pesoseco }} kg</p>
                                </div>
                            @endif

                            @if($paciente->talla)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Talla:</span>
                                    <p class="text-gray-900">{{ $paciente->talla }} cm</p>
                                </div>
                            @endif

                            @if($paciente->gruposanguineo)
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Grupo Sanguíneo:</span>
                                    <p class="text-gray-900">{{ $paciente->gruposanguineo }}</p>
                                </div>
                            @endif

                            <div>
                                <span class="text-sm font-medium text-gray-500">Fumador:</span>
                                <p class="text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $paciente->fumador ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $paciente->fumador ? 'Sí' : 'No' }}
                                    </span>
                                </p>
                            </div>

                            <div>
                                <span class="text-sm font-medium text-gray-500">Insulinodependiente:</span>
                                <p class="text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $paciente->insulinodependiente ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $paciente->insulinodependiente ? 'Sí' : 'No' }}
                                    </span>
                                </p>
                            </div>

                            <!-- Obras Sociales integradas en la información médica -->
                            @if($paciente->obrasSociales && $paciente->obrasSociales->count() > 0)
                                <div class="col-span-3 border-t pt-4">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Obra Social</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        @foreach($paciente->obrasSociales as $obra)
                                            <div class="border border-gray-200 rounded-lg p-3">
                                                <h4 class="font-medium text-gray-900">{{ $obra->abreviatura }}</h4>
                                                @if($obra->pivot && $obra->pivot->nroafiliado)
                                                    <p class="text-sm text-gray-600">
                                                        Nro. Afiliado: {{ $obra->pivot->nroafiliado }}
                                                    </p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                        </div>
                        
                    </div>

                </div>

            </div>


            <!-- Accesos Vasculares - Solo para pacientes de diálisis -->
            @if(!isset($esPacienteConsultorio) || !$esPacienteConsultorio)
                @if($paciente->accesosVasculares && $paciente->accesosVasculares->count() > 0)
                    <div class="bg-white shadow rounded-lg p-6 mt-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Accesos Vasculares</h2>
                        <div class="space-y-4">
                            @foreach($paciente->accesosVasculares as $acceso)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-medium text-gray-900">
                                                {{ $acceso->tipoAccesoVascular->nombre ?? 'Tipo no especificado' }}
                                            </h3>
                                            @if($acceso->fechaacceso)
                                                <p class="text-sm text-gray-600">
                                                    Fecha: {{ \Carbon\Carbon::parse($acceso->fechaacceso)->format('d/m/Y') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    @if($acceso->observaciones)
                                        <p class="text-sm text-gray-700 mt-2">{{ $acceso->observaciones }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif

            <!-- Historias Clínicas Recientes -->
            @php
                $historias = isset($esPacienteConsultorio) && $esPacienteConsultorio 
                    ? ($paciente->historiasClinicasConsultorio ?? collect()) 
                    : ($paciente->historiasClinicas ?? collect());
            @endphp
            
            @if($historias && $historias->count() > 0)
                <div class="bg-white shadow rounded-lg p-6 mt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">
                            Historias Clínicas Recientes
                            @if(isset($esPacienteConsultorio) && $esPacienteConsultorio)
                                <span class="text-sm text-gray-500">(Consultorio)</span>
                            @endif
                        </h2>
                        <a href="{{ isset($esPacienteConsultorio) && $esPacienteConsultorio 
                                    ? route('historias-clinicas-consultorio.create', $paciente->id) 
                                    : route('historias-clinicas.create', $paciente->id) }}"
                           style="background-color:#009999" class="hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Nueva Historia Clínica
                        </a>
                    </div>
                    <div class="space-y-4">
                        @foreach($historias as $historia)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-medium text-gray-900">Historia Clínica</h3>
                                    @if($historia->fechahistoriaclinica)
                                        <span class="text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($historia->fechahistoriaclinica)->format('d/m/Y H:i') }}
                                        </span>
                                    @endif
                                </div>
                                @if($historia->observaciones)
                                    <p class="text-gray-700">{{ $historia->observaciones }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
