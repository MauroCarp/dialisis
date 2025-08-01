<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-x-3">
                <x-heroicon-o-magnifying-glass class="h-6 w-6" />
                Buscador de Pacientes
            </div>
        </x-slot>

        <div class="space-y-6">
            <!-- Formulario de búsqueda -->
            <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-800">
                {{ $this->form }}
            </div>

            <!-- Resultados de búsqueda -->
            @if(count($resultadosBusqueda) > 0)
                <div class="border border-gray-200 rounded-lg dark:border-gray-700">
                    <div class="bg-gray-50 px-4 py-2 border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                        <h3 class="font-medium text-gray-900 dark:text-gray-100">
                            Resultados encontrados ({{ count($resultadosBusqueda) }})
                        </h3>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($resultadosBusqueda as $paciente)
                            <div class="p-4 hover:bg-gray-50 cursor-pointer dark:hover:bg-gray-800 transition-colors"
                                 wire:click="seleccionarPaciente({{ $paciente['id'] }})">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $paciente['apellido'] ?? 'N/A' }}, {{ $paciente['nombre'] ?? 'N/A' }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            DNI/CUIL/CUIT: {{ $paciente['dnicuitcuil'] ?? 'N/A' }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Nro. Alta: {{ $paciente['nroalta'] ?? 'N/A' }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                   {{ $tipoTabla === 'hemodialisis' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $tipoTabla === 'hemodialisis' ? 'Hemodiálisis' : 'Consultorio' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Información detallada del paciente seleccionado -->
            @if($pacienteSeleccionado)
                <div class="border border-gray-200 rounded-lg overflow-hidden dark:border-gray-700">
                    <!-- Header del paciente -->
                    <div class="bg-primary-50 px-6 py-4 border-b border-gray-200 dark:bg-primary-900/20 dark:border-gray-700">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ $pacienteSeleccionado['apellido'] }}, {{ $pacienteSeleccionado['nombre'] }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $tipoTabla === 'hemodialisis' ? 'Paciente de Hemodiálisis' : 'Paciente de Consultorio' }} 
                                    - Nro. Alta: {{ $pacienteSeleccionado['nroalta'] ?? 'N/A' }}
                                </p>
                            </div>
                            <button wire:click="limpiarBusqueda" 
                                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <x-heroicon-o-x-mark class="h-6 w-6" />
                            </button>
                        </div>
                    </div>

                    <!-- Contenido del paciente en pestañas -->
                    <div class="p-6">
                        <div x-data="{ activeTab: 'general' }" class="space-y-4">
                            <!-- Pestañas -->
                            <div class="border-b border-gray-200 dark:border-gray-700">
                                <nav class="-mb-px flex space-x-8">
                                    <button @click="activeTab = 'general'" 
                                            :class="activeTab === 'general' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                            class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                        Información General
                                    </button>
                                    @if($tipoTabla === 'hemodialisis')
                                        <button @click="activeTab = 'medica'" 
                                                :class="activeTab === 'medica' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                            Información Médica
                                        </button>
                                        <button @click="activeTab = 'analisis'" 
                                                :class="activeTab === 'analisis' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                            Análisis Recientes
                                        </button>
                                        <button @click="activeTab = 'historial'" 
                                                :class="activeTab === 'historial' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                            Historial Médico
                                        </button>
                                    @else
                                        <button @click="activeTab = 'historias'" 
                                                :class="activeTab === 'historias' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                                class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                            Historias Clínicas
                                        </button>
                                    @endif
                                </nav>
                            </div>

                            <!-- Contenido de las pestañas -->
                            
                            <!-- Pestaña: Información General -->
                            <div x-show="activeTab === 'general'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100">Datos Personales</h4>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Documento:</span>
                                            <p class="text-gray-900 dark:text-gray-100">
                                                {{ $pacienteSeleccionado['tipo_documento']['descripcion'] ?? 'N/A' }}: 
                                                {{ $pacienteSeleccionado['dnicuitcuil'] ?? 'N/A' }}
                                            </p>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Fecha Nacimiento:</span>
                                            <p class="text-gray-900 dark:text-gray-100">
                                                {{ $pacienteSeleccionado['fechanacimiento'] ? \Carbon\Carbon::parse($pacienteSeleccionado['fechanacimiento'])->format('d/m/Y') : 'N/A' }}
                                            </p>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Teléfono:</span>
                                            <p class="text-gray-900 dark:text-gray-100">{{ $pacienteSeleccionado['telefono'] ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Email:</span>
                                            <p class="text-gray-900 dark:text-gray-100">{{ $pacienteSeleccionado['email'] ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-span-2">
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Dirección:</span>
                                            <p class="text-gray-900 dark:text-gray-100">{{ $pacienteSeleccionado['direccion'] ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-span-2">
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Localidad:</span>
                                            <p class="text-gray-900 dark:text-gray-100">
                                                {{ $pacienteSeleccionado['localidad']['nombre'] ?? 'N/A' }}, 
                                                {{ $pacienteSeleccionado['localidad']['provincia']['nombre'] ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100">Datos Médicos</h4>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Peso Seco:</span>
                                            <p class="text-gray-900 dark:text-gray-100">{{ $pacienteSeleccionado['pesoseco'] ?? 'N/A' }} kg</p>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Talla:</span>
                                            <p class="text-gray-900 dark:text-gray-100">{{ $pacienteSeleccionado['talla'] ?? 'N/A' }} cm</p>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Grupo Sanguíneo:</span>
                                            <p class="text-gray-900 dark:text-gray-100">{{ $pacienteSeleccionado['gruposanguineo'] ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Fumador:</span>
                                            <p class="text-gray-900 dark:text-gray-100">
                                                {{ $pacienteSeleccionado['fumador'] ? 'Sí' : 'No' }}
                                            </p>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Insulinodependiente:</span>
                                            <p class="text-gray-900 dark:text-gray-100">
                                                {{ $pacienteSeleccionado['insulinodependiente'] ? 'Sí' : 'No' }}
                                            </p>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Fecha Ingreso:</span>
                                            <p class="text-gray-900 dark:text-gray-100">
                                                {{ $pacienteSeleccionado['fechaingreso'] ? \Carbon\Carbon::parse($pacienteSeleccionado['fechaingreso'])->format('d/m/Y') : 'N/A' }}
                                            </p>
                                        </div>
                                        @if($tipoTabla === 'consultorio' && isset($pacienteSeleccionado['derivante']))
                                            <div class="col-span-2">
                                                <span class="font-medium text-gray-700 dark:text-gray-300">Derivante:</span>
                                                <p class="text-gray-900 dark:text-gray-100">{{ $pacienteSeleccionado['derivante'] ?? 'N/A' }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($tipoTabla === 'hemodialisis')
                                <!-- Pestaña: Información Médica -->
                                <div x-show="activeTab === 'medica'" class="space-y-6">
                                    <!-- Obras Sociales -->
                                    @if(isset($pacienteSeleccionado['obras_sociales']) && count($pacienteSeleccionado['obras_sociales']) > 0)
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Obras Sociales</h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                @foreach($pacienteSeleccionado['obras_sociales'] as $obra)
                                                    <div class="border border-gray-200 rounded-lg p-3 dark:border-gray-700">
                                                        <p class="font-medium">{{ $obra['descripcion'] }}</p>
                                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                                            Nro. Afiliado: {{ $obra['pivot']['nroafiliado'] ?? 'N/A' }}
                                                        </p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Accesos Vasculares -->
                                    @if(isset($pacienteSeleccionado['accesos_vasculares']) && count($pacienteSeleccionado['accesos_vasculares']) > 0)
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Accesos Vasculares</h4>
                                            <div class="space-y-3">
                                                @foreach($pacienteSeleccionado['accesos_vasculares'] as $acceso)
                                                    <div class="border border-gray-200 rounded-lg p-3 dark:border-gray-700">
                                                        <div class="flex justify-between items-start">
                                                            <div>
                                                                <p class="font-medium">{{ $acceso['tipo_acceso_vascular']['nombre'] ?? 'N/A' }}</p>
                                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                                    Fecha: {{ $acceso['fechaacceso'] ? \Carbon\Carbon::parse($acceso['fechaacceso'])->format('d/m/Y') : 'N/A' }}
                                                                </p>
                                                                @if(isset($acceso['cirujano']))
                                                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                                                        Cirujano: {{ $acceso['cirujano']['nombre'] }} {{ $acceso['cirujano']['apellido'] }}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @if($acceso['observaciones'])
                                                            <p class="text-sm mt-2 text-gray-700 dark:text-gray-300">{{ $acceso['observaciones'] }}</p>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Pestaña: Análisis Recientes -->
                                <div x-show="activeTab === 'analisis'" class="space-y-6">
                                    @if(isset($pacienteSeleccionado['analisis_diarios']) && count($pacienteSeleccionado['analisis_diarios']) > 0)
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Últimos Análisis Diarios</h4>
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                    <thead class="bg-gray-50 dark:bg-gray-800">
                                                        <tr>
                                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Fecha</th>
                                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Peso Pre</th>
                                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Peso Post</th>
                                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">TA Pre</th>
                                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">TA Post</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                                                        @foreach($pacienteSeleccionado['analisis_diarios'] as $analisis)
                                                            <tr>
                                                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                                    {{ $analisis['fechaanalisis'] ? \Carbon\Carbon::parse($analisis['fechaanalisis'])->format('d/m/Y') : 'N/A' }}
                                                                </td>
                                                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $analisis['pesopre'] ?? 'N/A' }}</td>
                                                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $analisis['pesopost'] ?? 'N/A' }}</td>
                                                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                                    {{ $analisis['taspre'] ?? 'N/A' }}/{{ $analisis['tadpre'] ?? 'N/A' }}
                                                                </td>
                                                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                                    {{ $analisis['taspos'] ?? 'N/A' }}/{{ $analisis['tadpos'] ?? 'N/A' }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Pestaña: Historial Médico -->
                                <div x-show="activeTab === 'historial'" class="space-y-6">
                                    <!-- Historias Clínicas -->
                                    @if(isset($pacienteSeleccionado['historias_clinicas']) && count($pacienteSeleccionado['historias_clinicas']) > 0)
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Historias Clínicas Recientes</h4>
                                            <div class="space-y-3">
                                                @foreach($pacienteSeleccionado['historias_clinicas'] as $historia)
                                                    <div class="border border-gray-200 rounded-lg p-3 dark:border-gray-700">
                                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                                            {{ $historia['fechahistoriaclinica'] ? \Carbon\Carbon::parse($historia['fechahistoriaclinica'])->format('d/m/Y H:i') : 'N/A' }}
                                                        </p>
                                                        <p class="text-gray-900 dark:text-gray-100">{{ $historia['observaciones'] ?? 'Sin observaciones' }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Transfusiones -->
                                    @if(isset($pacienteSeleccionado['transfusiones']) && count($pacienteSeleccionado['transfusiones']) > 0)
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Transfusiones Recientes</h4>
                                            <div class="space-y-3">
                                                @foreach($pacienteSeleccionado['transfusiones'] as $transfusion)
                                                    <div class="border border-gray-200 rounded-lg p-3 dark:border-gray-700">
                                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                                            {{ $transfusion['fechatransfusion'] ? \Carbon\Carbon::parse($transfusion['fechatransfusion'])->format('d/m/Y H:i') : 'N/A' }}
                                                        </p>
                                                        <p class="text-gray-900 dark:text-gray-100">{{ $transfusion['observaciones'] ?? 'Sin observaciones' }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Internaciones -->
                                    @if(isset($pacienteSeleccionado['internaciones']) && count($pacienteSeleccionado['internaciones']) > 0)
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Internaciones Recientes</h4>
                                            <div class="space-y-3">
                                                @foreach($pacienteSeleccionado['internaciones'] as $internacion)
                                                    <div class="border border-gray-200 rounded-lg p-3 dark:border-gray-700">
                                                        <div class="flex justify-between items-start mb-2">
                                                            <p class="font-medium text-gray-900 dark:text-gray-100">
                                                                {{ $internacion['motivo_internacion']['nombre'] ?? 'Motivo no especificado' }}
                                                            </p>
                                                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                                                {{ $internacion['fechainiciointernacion'] ? \Carbon\Carbon::parse($internacion['fechainiciointernacion'])->format('d/m/Y') : 'N/A' }}
                                                                @if($internacion['fechafininternacion'])
                                                                    - {{ \Carbon\Carbon::parse($internacion['fechafininternacion'])->format('d/m/Y') }}
                                                                @endif
                                                            </span>
                                                        </div>
                                                        @if($internacion['observaciones'])
                                                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $internacion['observaciones'] }}</p>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <!-- Pestaña: Historias Clínicas de Consultorio -->
                                <div x-show="activeTab === 'historias'" class="space-y-6">
                                    @if(isset($pacienteSeleccionado['historias_clinicas_consultorio']) && count($pacienteSeleccionado['historias_clinicas_consultorio']) > 0)
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">Historias Clínicas de Consultorio</h4>
                                            <div class="space-y-3">
                                                @foreach($pacienteSeleccionado['historias_clinicas_consultorio'] as $historia)
                                                    <div class="border border-gray-200 rounded-lg p-3 dark:border-gray-700">
                                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                                            {{ $historia['fechahistoriaclinica'] ? \Carbon\Carbon::parse($historia['fechahistoriaclinica'])->format('d/m/Y H:i') : 'N/A' }}
                                                        </p>
                                                        <p class="text-gray-900 dark:text-gray-100">{{ $historia['observaciones'] ?? 'Sin observaciones' }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
