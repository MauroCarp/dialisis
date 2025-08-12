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
            <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center">
                    <div class="text-gray-500">
                        <h1 class="text-3xl font-bold mb-2">
                            <i class="fas fa-user-circle mr-3"></i>
                            {{ $paciente->nombre }} {{ $paciente->apellido }}
                        </h1>
                        <p class="text-teal-800 text-lg">
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
                        <a href="{{ isset($esPacienteConsultorio) && $esPacienteConsultorio ? url('admin/pacientes-consultorios/' . $paciente->id . '/edit') : url('admin/pacientes/' . $paciente->id . '/edit') }}" 
                           class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
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
                <div x-data="{ 
                    activeMainTab: @if(session('show_tab') == 'internaciones' || session('show_tab') == 'transfusiones' || session('show_tab') == 'accesos') 'grupo3' 
                                  @elseif(session('show_tab')) 'grupo2' 
                                  @else 'grupo1' 
                                  @endif,
                    activeMedicalTab: @if(session('show_tab') && (session('show_tab') != 'internaciones' && session('show_tab') != 'transfusiones' && session('show_tab') != 'accesos')) '{{session('show_tab')}}' @else 'historias' @endif,
                    activeEventTab: @if(session('show_tab')) '{{session('show_tab')}}' @else 'accesos' @endif
                }" class="w-full">
                    <!-- Navegación de pestañas principales (3 grupos) -->
                    <div class="flex border-b border-gray-200 mb-6">
                        <button 
                            @click="activeMainTab = 'grupo1'"
                            :class="activeMainTab === 'grupo1' ? 'border-teal-500 text-teal-600 bg-teal-50' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="whitespace-nowrap py-3 px-8 border-b-2 font-medium text-lg focus:outline-none transition-colors duration-200"
                        >
                            <i class="fas fa-user mr-2"></i>
                            Datos del Paciente
                        </button>
                        
                        <button 
                            @click="activeMainTab = 'grupo2'"
                            :class="activeMainTab === 'grupo2' ? 'border-teal-500 text-teal-600 bg-teal-50' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="whitespace-nowrap py-3 px-8 border-b-2 font-medium text-lg focus:outline-none transition-colors duration-200"
                        >
                            <i class="fas fa-user-md mr-2"></i>
                            Información Médica
                            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full ml-2">
                                @php
                                    $historiasCount = 0;
                                    if(isset($esPacienteConsultorio) && $esPacienteConsultorio) {
                                        $historiasCount = $paciente->historiasClinicasConsultorio ? $paciente->historiasClinicasConsultorio->count() : 0;
                                    } else {
                                        $historiasCount = $paciente->historiasClinicas ? $paciente->historiasClinicas->count() : 0;
                                    }
                                @endphp
                                {{ $historiasCount + ($paciente->estudiosPacientes ? $paciente->estudiosPacientes->count() : 0) + ($paciente->patologiasPacientes ? $paciente->patologiasPacientes->count() : 0) + ($paciente->medicacionesPacientes ? $paciente->medicacionesPacientes->count() : 0) + ($paciente->vacunasPacientes ? $paciente->vacunasPacientes->count() : 0) + ((isset($analisisData['diarios']) ? $analisisData['diarios']->count() : 0) + (isset($analisisData['mensuales']) ? $analisisData['mensuales']->count() : 0) + (isset($analisisData['trimestrales']) ? $analisisData['trimestrales']->count() : 0) + (isset($analisisData['semestrales']) ? $analisisData['semestrales']->count() : 0)) }}
                            </span>
                        </button>
                        
                        <button 
                            @click="activeMainTab = 'grupo3'"
                            :class="activeMainTab === 'grupo3' ? 'border-teal-500 text-teal-600 bg-teal-50' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="whitespace-nowrap py-3 px-8 border-b-2 font-medium text-lg focus:outline-none transition-colors duration-200"
                        >
                            <i class="fas fa-procedures mr-2"></i>
                            Eventos Médicos
                            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full ml-2">
                                {{ ($paciente->accesosVasculares ? $paciente->accesosVasculares->count() : 0) + ($paciente->internaciones ? $paciente->internaciones->count() : 0) + ($paciente->transfusiones ? $paciente->transfusiones->count() : 0) }}
                            </span>
                        </button>
                    </div>

                    <!-- Contenido de los grupos -->
                    
                    <!-- GRUPO 1: Datos del Paciente -->
                    <div x-show="activeMainTab === 'grupo1'" x-transition class="space-y-6">
                        @include('pacientes.partials.datos-paciente')
                    </div>

                    <!-- GRUPO 2: Información Médica -->
                    <div x-show="activeMainTab === 'grupo2'" x-transition class="space-y-4">
                        <!-- Sub-pestañas para Información Médica -->
                        <div class="border-b border-gray-100 mb-4">
                            <div class="flex space-x-4">
                                <button 
                                    @click="activeMedicalTab = 'historias'"
                                    :class="activeMedicalTab === 'historias' ? 'border-blue-500 text-blue-600 bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                    class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200"
                                >
                                    <i class="fas fa-file-medical mr-1"></i>
                                    Historias Clínicas
                                    <span class="bg-gray-100 text-gray-600 text-xs px-1.5 py-0.5 rounded-full ml-1">
                                        @if(isset($esPacienteConsultorio) && $esPacienteConsultorio)
                                            {{ $paciente->historiasClinicasConsultorio ? $paciente->historiasClinicasConsultorio->count() : 0 }}
                                        @else
                                            {{ $paciente->historiasClinicas ? $paciente->historiasClinicas->count() : 0 }}
                                        @endif
                                    </span>
                                </button>
                                
                                @if(!isset($esPacienteConsultorio) || !$esPacienteConsultorio)
                                <button 
                                    @click="activeMedicalTab = 'analisis'"
                                    :class="activeMedicalTab === 'analisis' ? 'border-blue-500 text-blue-600 bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                    class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200"
                                >
                                    <i class="fas fa-flask mr-1"></i>
                                    Análisis
                                    <span class="bg-gray-100 text-gray-600 text-xs px-1.5 py-0.5 rounded-full ml-1">
                                        {{ (isset($analisisData['diarios']) ? $analisisData['diarios']->count() : 0) + (isset($analisisData['mensuales']) ? $analisisData['mensuales']->count() : 0) + (isset($analisisData['trimestrales']) ? $analisisData['trimestrales']->count() : 0) + (isset($analisisData['semestrales']) ? $analisisData['semestrales']->count() : 0) }}
                                    </span>
                                </button>
                                @endif
                                
                                <button 
                                    @click="activeMedicalTab = 'patologias'"
                                    :class="activeMedicalTab === 'patologias' ? 'border-blue-500 text-blue-600 bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                    class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200"
                                >
                                    <i class="fas fa-diagnoses mr-1"></i>
                                    Patologías
                                    <span class="bg-gray-100 text-gray-600 text-xs px-1.5 py-0.5 rounded-full ml-1">{{ $paciente->patologiasPacientes ? $paciente->patologiasPacientes->count() : 0 }}</span>
                                </button>
                                
                                <button 
                                    @click="activeMedicalTab = 'estudios'"
                                    :class="activeMedicalTab === 'estudios' ? 'border-blue-500 text-blue-600 bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                    class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200"
                                >
                                    <i class="fas fa-microscope mr-1"></i>
                                    Estudios
                                    <span class="bg-gray-100 text-gray-600 text-xs px-1.5 py-0.5 rounded-full ml-1">{{ $paciente->estudiosPacientes ? $paciente->estudiosPacientes->count() : 0 }}</span>
                                </button>

                                <button 
                                    @click="activeMedicalTab = 'medicaciones'"
                                    :class="activeMedicalTab === 'medicaciones' ? 'border-blue-500 text-blue-600 bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                    class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200"
                                >
                                    <i class="fas fa-pills mr-1"></i>
                                    Medicaciones
                                    <span class="bg-gray-100 text-gray-600 text-xs px-1.5 py-0.5 rounded-full ml-1">{{ $paciente->medicacionesPacientes ? $paciente->medicacionesPacientes->count() : 0 }}</span>
                                </button>
                                
                                <button 
                                    @click="activeMedicalTab = 'vacunas'"
                                    :class="activeMedicalTab === 'vacunas' ? 'border-blue-500 text-blue-600 bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                    class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200"
                                >
                                    <i class="fas fa-syringe mr-1"></i>
                                    Vacunas
                                    <span class="bg-gray-100 text-gray-600 text-xs px-1.5 py-0.5 rounded-full ml-1">{{ $paciente->vacunasPacientes ? $paciente->vacunasPacientes->count() : 0 }}</span>
                                </button>
                            </div>
                        </div>

                        <!-- Contenido de las sub-pestañas médicas -->
                        <div x-show="activeMedicalTab === 'historias'" x-transition class="space-y-4">
                            @include('pacientes.partials.historias-clinicas')
                        </div>
                        
                        @if(!isset($esPacienteConsultorio) || !$esPacienteConsultorio)
                        <div x-show="activeMedicalTab === 'analisis'" x-transition class="space-y-4">
                            @include('pacientes.partials.analisis')
                        </div>
                        @endif
                        
                        <div x-show="activeMedicalTab === 'patologias'" x-transition class="space-y-4">
                            @include('pacientes.partials.patologias-pacientes')
                        </div>
                        
                        <div x-show="activeMedicalTab === 'estudios'" x-transition class="space-y-4">
                            @include('pacientes.partials.estudios-pacientes')
                        </div>
                        
                        <div x-show="activeMedicalTab === 'medicaciones'" x-transition class="space-y-4">
                            @include('pacientes.partials.medicaciones-pacientes')
                        </div>
                        
                        <div x-show="activeMedicalTab === 'vacunas'" x-transition class="space-y-4">
                            @include('pacientes.partials.vacunas-pacientes')
                        </div>
                    </div>

                    <!-- GRUPO 3: Eventos Médicos -->
                    <div x-show="activeMainTab === 'grupo3'" x-transition class="space-y-4">
                        <!-- Sub-pestañas para Eventos Médicos -->
                        <div class="border-b border-gray-100 mb-4">
                            <div class="flex space-x-4">
                                @if(!isset($esPacienteConsultorio) || !$esPacienteConsultorio)
                                <button 
                                    @click="activeEventTab = 'accesos'"
                                    :class="activeEventTab === 'accesos' ? 'border-green-500 text-green-600 bg-green-50' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                    class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200"
                                >
                                    <i class="fas fa-heart mr-1"></i>
                                    Accesos Vasculares
                                    <span class="bg-gray-100 text-gray-600 text-xs px-1.5 py-0.5 rounded-full ml-1">{{ $paciente->accesosVasculares ? $paciente->accesosVasculares->count() : 0 }}</span>
                                </button>
                                @endif
                                
                                <button 
                                    @click="activeEventTab = 'internaciones'"
                                    :class="activeEventTab === 'internaciones' ? 'border-green-500 text-green-600 bg-green-50' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                    class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200"
                                >
                                    <i class="fas fa-hospital mr-1"></i>
                                    Internaciones
                                    <span class="bg-gray-100 text-gray-600 text-xs px-1.5 py-0.5 rounded-full ml-1">{{ $paciente->internaciones ? $paciente->internaciones->count() : 0 }}</span>
                                </button>
                                
                                <button 
                                    @click="activeEventTab = 'transfusiones'"
                                    :class="activeEventTab === 'transfusiones' ? 'border-green-500 text-green-600 bg-green-50' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                    class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm focus:outline-none transition-colors duration-200"
                                >
                                    <i class="fas fa-tint mr-1"></i>
                                    Transfusiones
                                    <span class="bg-gray-100 text-gray-600 text-xs px-1.5 py-0.5 rounded-full ml-1">{{ $paciente->transfusiones ? $paciente->transfusiones->count() : 0 }}</span>
                                </button>
                            </div>
                        </div>

                        <!-- Contenido de las sub-pestañas de eventos -->
                        @if(!isset($esPacienteConsultorio) || !$esPacienteConsultorio)
                        <div x-show="activeEventTab === 'accesos'" x-transition class="space-y-4">
                            @include('pacientes.partials.accesos-vasculares')
                        </div>
                        @endif
                        
                        <div x-show="activeEventTab === 'internaciones'" x-transition class="space-y-4">
                            @include('pacientes.partials.internaciones')
                        </div>
                        
                        <div x-show="activeEventTab === 'transfusiones'" x-transition class="space-y-4">
                            @include('pacientes.partials.transfusiones')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
