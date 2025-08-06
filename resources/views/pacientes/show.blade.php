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
                                    Sala
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
                        <a href="../admin/{{(isset($esPacienteConsultorio) && $esPacienteConsultorio) ? 'pacientes-consultorios' : 'pacientes'}}/{{ $paciente->id }}/edit" 
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
                            <div class="col-span-3 border-t pt-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Obra Social</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @if($paciente->obrasSociales && $paciente->obrasSociales->count() > 0)
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
                                    @else
                                        <p class="text-gray-600">No tiene obras sociales registradas.</p>
                                    @endif
                                    </div>
                                </div>
                            
                        </div>
                        
                    </div>

                </div>

            </div>

            <!-- Sección de Análisis - Solo para pacientes de diálisis -->
            @if(!isset($esPacienteConsultorio) || !$esPacienteConsultorio)
            <div class="bg-white shadow rounded-lg p-6 mt-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Análisis</h2>
                
                <!-- Pestañas de Análisis -->
                <div x-data="{ activeTab: 'diarios' }" class="w-full">
                    <!-- Navegación de pestañas -->
                    <div class="flex border-b border-gray-200 mb-6">
                        <button 
                            @click="activeTab = 'diarios'"
                            :class="activeTab === 'diarios' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm focus:outline-none"
                        >
                            Análisis Diarios ({{ $analisisData['diarios']->count() ?? 0 }})
                        </button>
                        <button 
                            @click="activeTab = 'mensuales'"
                            :class="activeTab === 'mensuales' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm focus:outline-none"
                        >
                            Análisis Mensuales ({{ $analisisData['mensuales']->count() ?? 0 }})
                        </button>
                        <button 
                            @click="activeTab = 'trimestrales'"
                            :class="activeTab === 'trimestrales' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm focus:outline-none"
                        >
                            Análisis Trimestrales ({{ $analisisData['trimestrales']->count() ?? 0 }})
                        </button>
                        <button 
                            @click="activeTab = 'semestrales'"
                            :class="activeTab === 'semestrales' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm focus:outline-none"
                        >
                            Análisis Semestrales ({{ $analisisData['semestrales']->count() ?? 0 }})
                        </button>
                    </div>

                    <!-- Contenido de las pestañas -->
                    
                    <!-- Análisis Diarios -->
                    <div x-show="activeTab === 'diarios'" x-transition class="space-y-4">
                        <!-- Formulario para nuevo Análisis Diario (siempre visible arriba de los registros) -->
                        <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-6">
                            <form method="POST" action="{{ route('analisis-diarios.store', $paciente->id) }}">
                                @csrf
                                <div class="mb-4 grid grid-cols-5 md:grid-cols-5 gap-4">
                                    <input type="hidden" name="fechaanalisis" value="{{ now()->format('Y-m-d') }}" class="w-full border rounded px-3 py-2" required>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Peso Pre (kg)</label>
                                        <input type="number" step="0.01" name="pesopre" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">TAS Pre</label>
                                        <input type="number" name="taspre" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">TAD Pre</label>
                                        <input type="number" name="tadpre" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Rel. Peso Seco/Peso Pre</label>
                                        <input type="number" step="0.01" name="relpesosecopesopre" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Interdiálitico </label>
                                        <input type="number" step="0.01" name="interdialitico" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Peso Post (kg)</label>
                                        <input type="number" step="0.01" name="pesopost" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">TAS Post</label>
                                        <input type="number" name="taspos" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">TAD Post</label>
                                        <input type="number" name="tadpos" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Tipo de Filtro</label>
                                        <select name="id_tipofiltro" required class="w-full border rounded px-3 py-2">
                                            <option value="">Seleccione...</option>
                                            @foreach($tiposFiltros as $filtro)
                                                <option value="{{ $filtro->id }}">{{ $filtro->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                </div>
                                <div class="flex justify-end">
                                    <button 
                                        type="submit"
                                        class="px-4 py-2 rounded bg-teal-600 hover:bg-teal-700 text-white font-bold"
                                    >Guardar</button>
                                </div>
                            </form>
                        </div>
                            <div x-data="{ open: false }" class="space-y-2">
                                <button 
                                    @click="open = !open"
                                    class="w-full flex items-center justify-between px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded font-semibold text-gray-700 focus:outline-none"
                                    type="button"
                                >
                                    <span>
                                        <span x-show="!open">Mostrar</span>
                                        <span x-show="open">Ocultar</span>
                                        Análisis Diarios ({{ $analisisData['diarios']->count() }})
                                    </span>
                                    <svg x-show="!open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                                    <svg x-show="open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"></path></svg>
                                </button>
                                <div x-show="open" class="space-y-4" x-transition>
                                    @if(isset($analisisData['diarios']) && $analisisData['diarios']->count() > 0)

                                        @foreach($analisisData['diarios'] as $analisis)
                                            <div class="border border-gray-200 rounded-lg p-4">
                                                <div class="flex justify-between items-start mb-3">
                                                    @if($analisis->fechaanalisis)
                                                        <span class="text-sm text-gray-500">
                                                            {{ \Carbon\Carbon::parse($analisis->fechaanalisis)->format('d/m/Y') }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="grid grid-cols-5 md:grid-cols-5 gap-4 text-sm">
                                                    <div>
                                                        <span class="text-gray-500">Peso Pre:</span>
                                                        <p class="font-medium">{{ $analisis->pesopre ? $analisis->pesopre . ' kg' : 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">TAS Pre:</span>
                                                        <p class="font-medium">{{ $analisis->taspre ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">TAD Pre:</span>
                                                        <p class="font-medium">{{ $analisis->tadpre ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Rel. Peso Seco/Peso Pre:</span>
                                                        <p class="font-medium">{{ $analisis->relpesosecopesopre ?? 'N/A' }}</p>
                                                    </div>  
                                                    <div>
                                                        <span class="text-gray-500">Interdiálitico:</span>
                                                        <p class="font-medium">{{ $analisis->interdialitico ? $analisis->interdialitico . ' kg' : 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Peso Post:</span>
                                                        <p class="font-medium">{{ $analisis->pesopost ? $analisis->pesopost . ' kg' : 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">TAS Post:</span>
                                                        <p class="font-medium">{{ $analisis->taspos ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">TAD Post:</span>
                                                        <p class="font-medium">{{ $analisis->tadpos ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Tipo Filtro:</span>
                                                        <p class="font-medium">{{ $analisis->tipoFiltro->nombre ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                    @else
                                        <div class="text-center py-8 text-gray-500">
                                            <p>No hay análisis diarios registrados</p>
                                        </div>
                                    @endif

                                </div>
              
                            </div>
                        
                    </div>

                    <!-- Análisis Mensuales -->
                    <div x-show="activeTab === 'mensuales'" x-transition class="space-y-4">
                        <!-- Formulario para nuevo Análisis Mensual -->
                        <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-6">
                            <form method="POST" action="{{ route('analisis-mensuales.store', $paciente->id) }}">
                                @csrf
                                <div class="mb-4 grid grid-cols-6 md:grid-cols-6 gap-4">
                                        <input type="hidden" name="fechaanalisis" value="{{ now()->format('Y-m-d H:i:s') }}" class="w-full border rounded px-3 py-2">
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Protocolo</label>
                                        <input type="text" name="protocolo" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <!-- Hematología -->
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Hemoglobina</label>
                                        <input type="number" step="0.01" name="hemoglobina" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Hematocrito</label>
                                        <input type="number" step="0.01" name="hematocrito" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Rto. Blancos</label>
                                        <input type="number" step="0.01" name="rto_blancos" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Rto. Rojos</label>
                                        <input type="number" step="0.01" name="rto_rojos" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Rto. Plaquetas</label>
                                        <input type="number" step="0.01" name="rto_plaquetas" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <!-- Función Renal -->
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Creatinina</label>
                                        <input type="number" step="0.01" name="creatinina" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Urea Pre</label>
                                        <input type="number" step="0.01" name="uremia_pre" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Urea Post</label>
                                        <input type="number" step="0.01" name="uremia_post" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Urea/Creatinina</label>
                                        <input type="number" step="0.01" name="urea_creatinina" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <!-- Adecuación de Diálisis -->
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">URR (RPU)</label>
                                        <input type="number" step="0.01" name="rpu" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">KT/V Daugiras</label>
                                        <input type="number" step="0.01" name="ktv_daugiras" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">KT/V Basile</label>
                                        <input type="number" step="0.01" name="ktv_basile" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">TAC Urea</label>
                                        <input type="number" step="0.01" name="tac_urea" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <!-- Electrolitos -->
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Sodio</label>
                                        <input type="number" step="0.01" name="sodio" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Potasio</label>
                                        <input type="number" step="0.01" name="potasio" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Calcemia</label>
                                        <input type="number" step="0.01" name="calcemia" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Fosfatemia</label>
                                        <input type="number" step="0.01" name="fosfatemia" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <!-- Función Hepática -->
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">GPT</label>
                                        <input type="number" step="0.01" name="gpt" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">GOT</label>
                                        <input type="number" step="0.01" name="got" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Fosfatasa Alcalina</label>
                                        <input type="number" step="0.01" name="fosfatasa_alcalina" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <!-- Inflamación -->
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">PCR</label>
                                        <input type="number" step="0.01" name="pcr" class="w-full border rounded px-3 py-2">
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button 
                                        type="submit"
                                        class="px-4 py-2 rounded bg-teal-600 hover:bg-teal-700 text-white font-bold"
                                    >Guardar</button>
                                </div>
                            </form>
                        </div>

                        <div x-data="{ open: false }" class="space-y-2">
                            <button 
                                @click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded font-semibold text-gray-700 focus:outline-none"
                                type="button"
                            >
                                <span>
                                    <span x-show="!open">Mostrar</span>
                                    <span x-show="open">Ocultar</span>
                                    Análisis Mensuales ({{ $analisisData['mensuales']->count() }})
                                </span>
                                <svg x-show="!open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                                <svg x-show="open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"></path></svg>
                            </button>
                            <div x-show="open" class="space-y-4" x-transition>
                                @if(isset($analisisData['mensuales']) && $analisisData['mensuales']->count() > 0)
                                    @foreach($analisisData['mensuales'] as $analisis)
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            <div class="flex justify-between items-start mb-3">
                                                @if($analisis->fechaanalisis)
                                                    <span class="text-sm text-gray-500">
                                                        {{ \Carbon\Carbon::parse($analisis->fechaanalisis)->format('d/m/Y') }}
                                                    </span>
                                                @endif
                                                @if($analisis->protocolo)
                                                    <span class="text-sm font-medium text-gray-700">
                                                        Protocolo: {{ $analisis->protocolo }}
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <!-- Hematología -->
                                            <div class="mb-4">
                                                <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">Hematología</h5>
                                                <div class="grid grid-cols-3 md:grid-cols-5 gap-4 text-sm">
                                                    <div>
                                                        <span class="text-gray-500">Hemoglobina:</span>
                                                        <p class="font-medium">{{ $analisis->hemoglobina ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Hematocrito:</span>
                                                        <p class="font-medium">{{ $analisis->hematocrito ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Rto. Blancos:</span>
                                                        <p class="font-medium">{{ $analisis->rto_blancos ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Rto. Rojos:</span>
                                                        <p class="font-medium">{{ $analisis->rto_rojos ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Rto. Plaquetas:</span>
                                                        <p class="font-medium">{{ $analisis->rto_plaquetas ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Función Renal -->
                                            <div class="mb-4">
                                                <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">Función Renal</h5>
                                                <div class="grid grid-cols-3 md:grid-cols-4 gap-4 text-sm">
                                                    <div>
                                                        <span class="text-gray-500">Creatinina:</span>
                                                        <p class="font-medium">{{ $analisis->creatinina ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Urea Pre:</span>
                                                        <p class="font-medium">{{ $analisis->uremia_pre ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Urea Post:</span>
                                                        <p class="font-medium">{{ $analisis->uremia_post ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Urea/Creatinina:</span>
                                                        <p class="font-medium">{{ $analisis->urea_creatinina ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Adecuación de Diálisis -->
                                            <div class="mb-4">
                                                <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">Adecuación de Diálisis</h5>
                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                                    <div>
                                                        <span class="text-gray-500">URR (RPU):</span>
                                                        <p class="font-medium">{{ $analisis->rpu ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">KT/V Daugiras:</span>
                                                        <p class="font-medium">{{ $analisis->ktv_daugiras ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">KT/V Basile:</span>
                                                        <p class="font-medium">{{ $analisis->ktv_basile ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">TAC Urea:</span>
                                                        <p class="font-medium">{{ $analisis->tac_urea ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Electrolitos -->
                                            <div class="mb-4">
                                                <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">Electrolitos</h5>
                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                                    <div>
                                                        <span class="text-gray-500">Sodio:</span>
                                                        <p class="font-medium">{{ $analisis->sodio ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Potasio:</span>
                                                        <p class="font-medium">{{ $analisis->potasio ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Calcemia:</span>
                                                        <p class="font-medium">{{ $analisis->calcemia ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Fosfatemia:</span>
                                                        <p class="font-medium">{{ $analisis->fosfatemia ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Función Hepática e Inflamación -->
                                            <div class="mb-2">
                                                <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">Función Hepática e Inflamación</h5>
                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                                    <div>
                                                        <span class="text-gray-500">GPT:</span>
                                                        <p class="font-medium">{{ $analisis->gpt ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">GOT:</span>
                                                        <p class="font-medium">{{ $analisis->got ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Fosfatasa Alcalina:</span>
                                                        <p class="font-medium">{{ $analisis->fosfatasa_alcalina ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">PCR:</span>
                                                        <p class="font-medium">{{ $analisis->pcr ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-8 text-gray-500">
                                        <p>No hay análisis mensuales registrados</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Análisis Trimestrales -->
                    <div x-show="activeTab === 'trimestrales'" x-transition class="space-y-4">
                        <!-- Formulario para nuevo Análisis Trimestral -->
                        <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-6">
                            <form method="POST" action="{{ route('analisis-trimestrales.store', $paciente->id) }}">
                                @csrf
                                <div class="mb-4 grid grid-cols-4 md:grid-cols-4 gap-4">
                                    <input type="hidden" name="fechaanalisis" value="{{ now()->format('Y-m-d H:i:s') }}" class="w-full border rounded px-3 py-2">
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Protocolo</label>
                                        <input type="text" name="protocolo" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <!-- Análisis Nutricional -->
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Albúmina</label>
                                        <input type="number" step="0.01" name="albumina" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Colesterol</label>
                                        <input type="number" step="0.01" name="colesterol" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Triglicéridos</label>
                                        <input type="number" step="0.01" name="trigliseridos" class="w-full border rounded px-3 py-2">
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button 
                                        type="submit"
                                        class="px-4 py-2 rounded bg-teal-600 hover:bg-teal-700 text-white font-bold"
                                    >Guardar</button>
                                </div>
                            </form>
                        </div>

                        <div x-data="{ open: false }" class="space-y-2">
                            <button 
                                @click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded font-semibold text-gray-700 focus:outline-none"
                                type="button"
                            >
                                <span>
                                    <span x-show="!open">Mostrar</span>
                                    <span x-show="open">Ocultar</span>
                                    Análisis Trimestrales ({{ $analisisData['trimestrales']->count() }})
                                </span>
                                <svg x-show="!open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                                <svg x-show="open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"></path></svg>
                            </button>
                            <div x-show="open" class="space-y-4" x-transition>
                                @if(isset($analisisData['trimestrales']) && $analisisData['trimestrales']->count() > 0)
                                    @foreach($analisisData['trimestrales'] as $analisis)
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            <div class="flex justify-between items-start mb-3">
                                                @if($analisis->fechaanalisis)
                                                    <span class="text-sm text-gray-500">
                                                        {{ \Carbon\Carbon::parse($analisis->fechaanalisis)->format('d/m/Y') }}
                                                    </span>
                                                @endif
                                                @if($analisis->protocolo)
                                                    <span class="text-sm font-medium text-gray-700">
                                                        Protocolo: {{ $analisis->protocolo }}
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <div class="mb-2">
                                                <div class="grid grid-cols-3 md:grid-cols-3 gap-4 text-sm">
                                                    <div>
                                                        <span class="text-gray-500">Albúmina:</span>
                                                        <p class="font-medium">{{ $analisis->albumina ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Colesterol:</span>
                                                        <p class="font-medium">{{ $analisis->colesterol ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Triglicéridos:</span>
                                                        <p class="font-medium">{{ $analisis->trigliseridos ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-8 text-gray-500">
                                        <p>No hay análisis trimestrales registrados</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Análisis Semestrales -->
                    <div x-show="activeTab === 'semestrales'" x-transition class="space-y-4">
                        <!-- Formulario para nuevo Análisis Semestral -->
                        <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-6">
                            <form method="POST" action="{{ route('analisis-semestrales.store', $paciente->id) }}">
                                @csrf
                                <div class="mb-4 grid grid-cols-5 md:grid-cols-5 gap-4">
                                    <input type="hidden" name="fechaanalisis" value="{{ now()->format('Y-m-d H:i:s') }}" class="w-full border rounded px-3 py-2">
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Protocolo</label>
                                        <input type="text" name="protocolo" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <!-- Serología Hepatitis B -->
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">HBsAg</label>
                                        <select name="hbsag" class="w-full border rounded px-3 py-2">
                                            <option value="">Seleccione...</option>
                                            <option value="1">Positivo</option>
                                            <option value="0">Negativo</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Anti-HBsAg</label>
                                        <select name="antihbsag" class="w-full border rounded px-3 py-2">
                                            <option value="">Seleccione...</option>
                                            <option value="1">Positivo</option>
                                            <option value="0">Negativo</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Valor Anti-HBsAg</label>
                                        <input type="number" step="0.01" name="valorantihbsag" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Anti-Core</label>
                                        <select name="anticore" class="w-full border rounded px-3 py-2">
                                            <option value="">Seleccione...</option>
                                            <option value="1">Positivo</option>
                                            <option value="0">Negativo</option>
                                        </select>
                                    </div>
                                    <!-- Serología Hepatitis C y HIV -->
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Anti-HCV</label>
                                        <select name="antihcv" class="w-full border rounded px-3 py-2">
                                            <option value="">Seleccione...</option>
                                            <option value="1">Positivo</option>
                                            <option value="0">Negativo</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Anti-HIV</label>
                                        <select name="antihiv" class="w-full border rounded px-3 py-2">
                                            <option value="">Seleccione...</option>
                                            <option value="1">Positivo</option>
                                            <option value="0">Negativo</option>
                                        </select>
                                    </div>
                                    <!-- Análisis Especializados -->
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">PTH</label>
                                        <input type="number" step="0.01" name="pth" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Ferritina</label>
                                        <input type="number" step="0.01" name="ferritina" class="w-full border rounded px-3 py-2">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-semibold mb-2">Ferremia</label>
                                        <input type="number" step="0.01" name="ferremia" class="w-full border rounded px-3 py-2">
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button 
                                        type="submit"
                                        class="px-4 py-2 rounded bg-teal-600 hover:bg-teal-700 text-white font-bold"
                                    >Guardar</button>
                                </div>
                            </form>
                        </div>

                        <div x-data="{ open: false }" class="space-y-2">
                            <button 
                                @click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded font-semibold text-gray-700 focus:outline-none"
                                type="button"
                            >
                                <span>
                                    <span x-show="!open">Mostrar</span>
                                    <span x-show="open">Ocultar</span>
                                    Análisis Semestrales ({{ $analisisData['semestrales']->count() }})
                                </span>
                                <svg x-show="!open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                                <svg x-show="open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"></path></svg>
                            </button>
                            <div x-show="open" class="space-y-4" x-transition>
                                @if(isset($analisisData['semestrales']) && $analisisData['semestrales']->count() > 0)
                                    @foreach($analisisData['semestrales'] as $analisis)
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            <div class="flex justify-between items-start mb-3">
                                                @if($analisis->fechaanalisis)
                                                    <span class="text-sm text-gray-500">
                                                        {{ \Carbon\Carbon::parse($analisis->fechaanalisis)->format('d/m/Y') }}
                                                    </span>
                                                @endif
                                                @if($analisis->protocolo)
                                                    <span class="text-sm font-medium text-gray-700">
                                                        Protocolo: {{ $analisis->protocolo }}
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <!-- Serología Hepatitis B -->
                                            <div class="mb-4">
                                                <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">Serología Hepatitis B</h5>
                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                                    <div>
                                                        <span class="text-gray-500">HBsAg:</span>
                                                        <p class="font-medium">{{ $analisis->hbsag ? 'Positivo' : 'Negativo' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Anti-HBsAg:</span>
                                                        <p class="font-medium">{{ $analisis->antihbsag ? 'Positivo' : 'Negativo' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Valor Anti-HBsAg:</span>
                                                        <p class="font-medium">{{ $analisis->valorantihbsag ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Anti-Core:</span>
                                                        <p class="font-medium">{{ $analisis->anticore ? 'Positivo' : 'Negativo' }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Serología Hepatitis C y HIV -->
                                            <div class="mb-4">
                                                <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">Serología Hepatitis C y HIV</h5>
                                                <div class="grid grid-cols-2 md:grid-cols-2 gap-4 text-sm">
                                                    <div>
                                                        <span class="text-gray-500">Anti-HCV:</span>
                                                        <p class="font-medium">{{ $analisis->antihcv ? 'Positivo' : 'Negativo' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Anti-HIV:</span>
                                                        <p class="font-medium">{{ $analisis->antihiv ? 'Positivo' : 'Negativo' }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Análisis Especializados -->
                                            <div class="mb-2">
                                                <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">Análisis Especializados</h5>
                                                <div class="grid grid-cols-3 md:grid-cols-3 gap-4 text-sm">
                                                    <div>
                                                        <span class="text-gray-500">PTH:</span>
                                                        <p class="font-medium">{{ $analisis->pth ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Ferritina:</span>
                                                        <p class="font-medium">{{ $analisis->ferritina ?? 'N/A' }}</p>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Ferremia:</span>
                                                        <p class="font-medium">{{ $analisis->ferremia ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-8 text-gray-500">
                                        <p>No hay análisis semestrales registrados</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif


            <!-- Accesos Vasculares - Solo para pacientes de diálisis -->
            @if(!isset($esPacienteConsultorio) || !$esPacienteConsultorio)
            <div class="bg-white shadow rounded-lg p-6 mt-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Accesos Vasculares</h2>
                    <!-- Botón para abrir el modal -->
                    <button 
                        type="button"
                        onclick="document.getElementById('modal-acceso-vascular').classList.remove('hidden')"
                        style="background-color:#009999"
                        class="hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Nuevo Acceso Vascular
                    </button>
                </div>

                <!-- Modal -->
                <div id="modal-acceso-vascular" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
                    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
                        <!-- Botón de cerrar -->
                        <button 
                            type="button"
                            onclick="document.getElementById('modal-acceso-vascular').classList.add('hidden')"
                            class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl font-bold"
                        >&times;</button>
                        <h3 class="text-lg font-semibold mb-4">Nuevo Acceso Vascular</h3>
                        <form 
                            method="POST" 
                            action="{{ route('accesos-vasculares.store', $paciente->id) }}"
                        >
                            @csrf
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tipo de Acceso</label>
                                <select name="tipo_acceso_vascular_id" required class="w-full border rounded px-3 py-2">
                                    <option value="">Seleccione...</option>
                                    @foreach($tiposAccesoVascular as $tipo)
                                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Fecha</label>
                                <input type="date" name="fechaacceso" class="w-full border rounded px-3 py-2" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Observaciones</label>
                                <textarea name="observaciones" class="w-full border rounded px-3 py-2"></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button 
                                    type="button"
                                    onclick="document.getElementById('modal-acceso-vascular').classList.add('hidden')"
                                    class="mr-2 px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-700"
                                >Cancelar</button>
                                <button 
                                    type="submit"
                                    class="px-4 py-2 rounded bg-teal-600 hover:bg-teal-700 text-white font-bold"
                                >Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>

                @if($paciente->accesosVasculares && $paciente->accesosVasculares->count() > 0)
                        <div x-data="{ open: false }" class="space-y-2">
                            <button 
                                @click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded font-semibold text-gray-700 focus:outline-none"
                                type="button"
                            >
                                <span>
                                    <span x-show="!open">Mostrar</span>
                                    <span x-show="open">Ocultar</span>
                                    Accesos Vasculares ({{ $paciente->accesosVasculares->count() }})
                                </span>
                                <svg x-show="!open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                                <svg x-show="open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"></path></svg>
                            </button>
                            <div x-show="open" class="space-y-4" x-transition>
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
                            <!-- Alpine.js CDN for collapse/expand -->
                            <script src="//unpkg.com/alpinejs" defer></script>
                        </div>
                        @endif
                    </div>
                @endif

            <!-- Historias Clínicas Recientes -->
            @php
                $historias = isset($esPacienteConsultorio) && $esPacienteConsultorio 
                    ? ($paciente->historiasClinicasConsultorio ?? collect()) 
                    : ($paciente->historiasClinicas ?? collect());
            @endphp
            
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
            @if($historias && $historias->count() > 0)

                    <div class="space-y-4">
                        <div x-data="{ open: false }" class="space-y-2">
                            <button 
                                @click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded font-semibold text-gray-700 focus:outline-none"
                                type="button"
                            >
                                <span>
                                    <span x-show="!open">Mostrar</span>
                                    <span x-show="open">Ocultar</span>
                                    Historias Clínicas ({{ $historias->count() }})
                                </span>
                                <svg x-show="!open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                                <svg x-show="open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"></path></svg>
                            </button>
                            <div x-show="open" class="space-y-4" x-transition>
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
                        <!-- Alpine.js CDN for collapse/expand -->
                        <script src="//unpkg.com/alpinejs" defer></script>
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
