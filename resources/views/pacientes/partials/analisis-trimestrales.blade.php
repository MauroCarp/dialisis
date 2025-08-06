<!-- Análisis Trimestrales -->
<div x-show="activeTab === 'trimestrales'" x-transition class="space-y-4">
    <!-- Formulario para nuevo Análisis Trimestral -->
    <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-6">
        <h4 class="text-md font-semibold text-gray-800 mb-4">
            <i class="fas fa-calendar-week mr-2 text-teal-600"></i>
            Nuevo Análisis Trimestral
        </h4>
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
                    class="px-4 py-2 rounded bg-teal-600 hover:bg-teal-700 text-white font-bold transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Guardar
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Análisis Trimestrales -->
    <div x-data="{ open: false }" class="space-y-2">
        <button 
            @click="open = !open"
            class="w-full flex items-center justify-between px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded font-semibold text-gray-700 focus:outline-none transition-colors duration-200"
            type="button">
            <span>
                <span x-show="!open">Mostrar</span>
                <span x-show="open">Ocultar</span>
                Análisis Trimestrales ({{ isset($analisisData['trimestrales']) ? $analisisData['trimestrales']->count() : 0 }})
            </span>
            <svg x-show="!open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
            <svg x-show="open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"></path></svg>
        </button>
        <div x-show="open" class="space-y-4" x-transition>
            @if(isset($analisisData['trimestrales']) && $analisisData['trimestrales']->count() > 0)
                @foreach($analisisData['trimestrales'] as $analisis)
                    <div class="border border-gray-200 rounded-lg p-4 bg-white">
                        <div class="flex justify-between items-start mb-3">
                            @if($analisis->fechaanalisis)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ \Carbon\Carbon::parse($analisis->fechaanalisis)->format('d/m/Y') }}
                                </span>
                            @endif
                            @if($analisis->protocolo)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-file-alt mr-1"></i>
                                    Protocolo: {{ $analisis->protocolo }}
                                </span>
                            @endif
                        </div>
                        
                        <div class="mb-2">
                            <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">
                                <i class="fas fa-apple-alt mr-1 text-green-500"></i>
                                Análisis Nutricional
                            </h5>
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
                    <i class="fas fa-flask text-gray-300 text-4xl mb-4"></i>
                    <p class="text-lg font-medium text-gray-400 mb-2">No hay análisis trimestrales registrados</p>
                    <p class="text-sm">Los análisis trimestrales aparecerán aquí una vez que se registren.</p>
                </div>
            @endif
        </div>
    </div>
</div>
