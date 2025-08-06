<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paciente {{ $paciente->nombre }} {{ $paciente->apellido }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="bg-gradient-to-r from-teal-600 to-teal-800 shadow-lg rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center">
                    <div class="text-white">
                        <h1 class="text-3xl font-bold mb-2">
                            <i class="fas fa-user-circle mr-3"></i>
                            {{ $paciente->nombre }} {{ $paciente->apellido }}
                        </h1>
                        <p class="text-teal-100 text-lg">
                            <i class="fas fa-id-card mr-2"></i>
                            DNI: {{ $paciente->dni ?? $paciente->dnicuitcuil ?? 'No especificado' }}
                            @if(isset($esPacienteConsultorio) && $esPacienteConsultorio)
                                <span class="ml-4 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-500 text-white">
                                    <i class="fas fa-clinic-medical mr-2"></i>
                                    Consultorio
                                </span>
                            @else
                                <span class="ml-4 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-500 text-white">
                                    <i class="fas fa-procedures mr-2"></i>
                                    Diálisis
                                </span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="flex space-x-3">
                        <a href="{{ route('pacientes.edit', $paciente->id) }}" 
                           class="bg-white hover:bg-gray-100 text-teal-600 font-bold py-2 px-4 rounded transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>
                            Editar
                        </a>
                        <a href="/admin" 
                           class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                            <i class="fas fa-home mr-2"></i>
                            Volver al Inicio
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Sistema de Pestañas Principal -->
            <div class="bg-white shadow rounded-lg p-6">
                <div x-data="{ activeMainTab: 'datos' }" class="w-full">
                    <!-- Navegación de pestañas principales -->
                    <div class="flex border-b border-gray-200 mb-6">
                        <button 
                            @click="activeMainTab = 'datos'"
                            :class="activeMainTab === 'datos' ? 'border-teal-500 text-teal-600 bg-teal-50' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="whitespace-nowrap py-3 px-6 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200"
                        >
                            <i class="fas fa-user mr-2"></i>
                            Datos del Paciente
                        </button>
                        
                        @if(!isset($esPacienteConsultorio) || !$esPacienteConsultorio)
                        <button 
                            @click="activeMainTab = 'analisis'"
                            :class="activeMainTab === 'analisis' ? 'border-teal-500 text-teal-600 bg-teal-50' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="whitespace-nowrap py-3 px-6 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200"
                        >
                            <i class="fas fa-flask mr-2"></i>
                            Análisis
                            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full ml-1">
                                {{ ($analisisData['diarios']->count() ?? 0) + ($analisisData['mensuales']->count() ?? 0) + ($analisisData['trimestrales']->count() ?? 0) + ($analisisData['semestrales']->count() ?? 0) }}
                            </span>
                        </button>
                        
                        <button 
                            @click="activeMainTab = 'accesos'"
                            :class="activeMainTab === 'accesos' ? 'border-teal-500 text-teal-600 bg-teal-50' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="whitespace-nowrap py-3 px-6 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200"
                        >
                            <i class="fas fa-heart mr-2"></i>
                            Accesos Vasculares 
                            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full ml-1">{{ $paciente->accesosVasculares->count() ?? 0 }}</span>
                        </button>
                        @endif
                        
                        <button 
                            @click="activeMainTab = 'historias'"
                            :class="activeMainTab === 'historias' ? 'border-teal-500 text-teal-600 bg-teal-50' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="whitespace-nowrap py-3 px-6 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200"
                        >
                            <i class="fas fa-file-medical mr-2"></i>
                            Historias Clínicas 
                            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full ml-1">{{ $paciente->historiaClinica->count() ?? 0 }}</span>
                        </button>
                    </div>

                    <!-- Contenido de las pestañas principales -->
                    
                    <!-- Pestaña: Datos del Paciente -->
                    <div x-show="activeMainTab === 'datos'" x-transition class="space-y-6">
                        @include('pacientes.partials.datos-paciente')
                    </div>

                    <!-- Pestaña: Análisis (solo para pacientes de diálisis) -->
                    @if(!isset($esPacienteConsultorio) || !$esPacienteConsultorio)
                    <div x-show="activeMainTab === 'analisis'" x-transition class="space-y-4">
                        @include('pacientes.partials.analisis')
                    </div>
                    
                    <!-- Pestaña: Accesos Vasculares (solo para pacientes de diálisis) -->
                    <div x-show="activeMainTab === 'accesos'" x-transition class="space-y-4">
                        @include('pacientes.partials.accesos-vasculares')
                    </div>
                    @endif

                    <!-- Pestaña: Historias Clínicas -->
                    <div x-show="activeMainTab === 'historias'" x-transition class="space-y-4">
                        @include('pacientes.partials.historias-clinicas')
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
