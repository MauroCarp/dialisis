<!-- Análisis Mensuales -->
<div x-show="activeTab === 'mensuales'" x-transition class="space-y-4">
    <!-- Formulario para nuevo Análisis Mensual -->
    <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-6">
        <h4 class="text-md font-semibold text-gray-800 mb-4">
            <i class="fas fa-calendar-alt mr-2 text-teal-600"></i>
            Nuevo Análisis Mensual
        </h4>
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
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Uremia Pre</label>
                    <input type="number" step="0.01" name="uremia_pre" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Uremia Post</label>
                    <input type="number" step="0.01" name="uremia_post" class="w-full border rounded px-3 py-2">
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

    <!-- Lista de Análisis Mensuales -->
    <div x-data="{ open: false }" class="space-y-2">
        <button 
            @click="open = !open"
            class="w-full flex items-center justify-between px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded font-semibold text-gray-700 focus:outline-none transition-colors duration-200"
            type="button">
            <span>
                <span x-show="!open">Mostrar</span>
                <span x-show="open">Ocultar</span>
                Análisis Mensuales ({{ isset($analisisData['mensuales']) ? $analisisData['mensuales']->count() : 0 }})
            </span>
            <svg x-show="!open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
            <svg x-show="open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"></path></svg>
        </button>
        <div x-show="open" class="space-y-4" x-transition>
            @if(isset($analisisData['mensuales']) && $analisisData['mensuales']->count() > 0)
                @foreach($analisisData['mensuales'] as $analisis)
                    <div class="border border-gray-200 rounded-lg p-4 bg-white">
                        <div class="flex justify-between items-start mb-3">
                            @if($analisis->fechaanalisis)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
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
                        
                        <!-- Hematología -->
                        <div class="mb-4">
                            <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">
                                <i class="fas fa-tint mr-1 text-red-500"></i>
                                Hematología
                            </h5>
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
                            <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">
                                <i class="fas fa-kidneys mr-1 text-orange-500"></i>
                                Función Renal
                            </h5>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Creatinina:</span>
                                    <p class="font-medium">{{ $analisis->creatinina ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Uremia Pre:</span>
                                    <p class="font-medium">{{ $analisis->uremia_pre ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Uremia Post:</span>
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
                            <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">
                                <i class="fas fa-chart-line mr-1 text-green-500"></i>
                                Adecuación de Diálisis
                            </h5>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">RPU:</span>
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
                            <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">
                                <i class="fas fa-atom mr-1 text-purple-500"></i>
                                Electrolitos
                            </h5>
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
                            <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">
                                <i class="fas fa-liver mr-1 text-yellow-500"></i>
                                Función Hepática e Inflamación
                            </h5>
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
                    <i class="fas fa-flask text-gray-300 text-4xl mb-4"></i>
                    <p class="text-lg font-medium text-gray-400 mb-2">No hay análisis mensuales registrados</p>
                    <p class="text-sm">Los análisis mensuales aparecerán aquí una vez que se registren.</p>
                </div>
            @endif
        </div>
    </div>
</div>
