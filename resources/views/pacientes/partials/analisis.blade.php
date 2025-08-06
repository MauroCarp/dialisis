<!-- Pestaña: Análisis (solo para pacientes de diálisis) -->
@if(!isset($esPacienteConsultorio) || !$esPacienteConsultorio)
<div x-show="activeMainTab === 'analisis'" x-transition class="space-y-4">
    <!-- Pestañas de Análisis -->
    <div x-data="{ activeTab: 'diarios' }" class="w-full">
        <!-- Navegación de pestañas -->
        <div class="flex border-b border-gray-200 mb-6">
            <button 
                @click="activeTab = 'diarios'"
                :class="activeTab === 'diarios' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm focus:outline-none"
            >
                Análisis Diarios ({{ isset($analisisData['diarios']) ? $analisisData['diarios']->count() : 0 }})
            </button>
            <button 
                @click="activeTab = 'mensuales'"
                :class="activeTab === 'mensuales' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm focus:outline-none"
            >
                Análisis Mensuales ({{ isset($analisisData['mensuales']) ? $analisisData['mensuales']->count() : 0 }})
            </button>
            <button 
                @click="activeTab = 'trimestrales'"
                :class="activeTab === 'trimestrales' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm focus:outline-none"
            >
                Análisis Trimestrales ({{ isset($analisisData['trimestrales']) ? $analisisData['trimestrales']->count() : 0 }})
            </button>
            <button 
                @click="activeTab = 'semestrales'"
                :class="activeTab === 'semestrales' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm focus:outline-none"
            >
                Análisis Semestrales ({{ isset($analisisData['semestrales']) ? $analisisData['semestrales']->count() : 0 }})
            </button>
        </div>

        <!-- Contenido de las pestañas de análisis -->
        @include('pacientes.partials.analisis-diarios')
        @include('pacientes.partials.analisis-mensuales')
        @include('pacientes.partials.analisis-trimestrales')
        @include('pacientes.partials.analisis-semestrales')
    </div>
</div>
@endif
