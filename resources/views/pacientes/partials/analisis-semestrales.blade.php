<!-- Análisis Semestrales -->
<div x-show="activeTab === 'semestrales'" x-transition class="space-y-4">
    <!-- Formulario para nuevo Análisis Semestral -->
    <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-6">
        <h4 class="text-md font-semibold text-gray-800 mb-4">
            <i class="fas fa-calendar-alt mr-2 text-indigo-600"></i>
            Nuevo Análisis Semestral
        </h4>
        <form method="POST" action="{{ route('analisis-semestrales.store', $paciente->id) }}">
            @csrf
            <div class="mb-4 grid grid-cols-2 md:grid-cols-3 gap-4">
                <input type="hidden" name="fechaanalisis" value="{{ now()->format('Y-m-d H:i:s') }}" class="w-full border rounded px-3 py-2">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Protocolo</label>
                    <input type="text" name="protocolo" class="w-full border rounded px-3 py-2">
                </div>
                
                <!-- Marcadores Virológicos -->
                <div class="col-span-2 md:col-span-3">
                    <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">
                        <i class="fas fa-virus mr-1 text-red-500"></i>
                        Marcadores Virológicos
                    </h5>
                </div>
                
                <div class="flex items-center">
                    <input type="hidden" name="hbsag" value="0">
                    <input type="checkbox" id="hbsag" name="hbsag" value="1" class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="hbsag" class="text-sm font-medium text-gray-700">HBsAg</label>
                </div>
                
                <div class="flex items-center">
                    <input type="hidden" name="antihbsag" value="0">
                    <input type="checkbox" id="antihbsag" name="antihbsag" value="1" class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="antihbsag" class="text-sm font-medium text-gray-700">Anti-HBsAg</label>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Valor Anti-HBsAg</label>
                    <input type="number" step="0.01" name="valorantihbsag" class="w-full border rounded px-3 py-2">
                </div>
                
                <div class="flex items-center">
                    <input type="hidden" name="antihcv" value="0">
                    <input type="checkbox" id="antihcv" name="antihcv" value="1" class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="antihcv" class="text-sm font-medium text-gray-700">Anti-HCV</label>
                </div>
                
                <div class="flex items-center">
                    <input type="hidden" name="antihiv" value="0">
                    <input type="checkbox" id="antihiv" name="antihiv" value="1" class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="antihiv" class="text-sm font-medium text-gray-700">Anti-HIV</label>
                </div>
                
                <div class="flex items-center">
                    <input type="hidden" name="anticore" value="0">
                    <input type="checkbox" id="anticore" name="anticore" value="1" class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="anticore" class="text-sm font-medium text-gray-700">Anti-Core</label>
                </div>
                
                <!-- Seguimiento Metabólico -->
                <div class="col-span-2 md:col-span-3">
                    <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">
                        <i class="fas fa-chart-line mr-1 text-blue-500"></i>
                        Seguimiento Metabólico
                    </h5>
                </div>
                
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
                    class="px-4 py-2 rounded bg-indigo-600 hover:bg-indigo-700 text-white font-bold transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Guardar
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Análisis Semestrales -->
    <div x-data="{ open: false }" class="space-y-2">
        <button 
            @click="open = !open"
            class="w-full flex items-center justify-between px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded font-semibold text-gray-700 focus:outline-none transition-colors duration-200"
            type="button">
            <span>
                <span x-show="!open">Mostrar</span>
                <span x-show="open">Ocultar</span>
                Análisis Semestrales ({{ isset($analisisData['semestrales']) ? $analisisData['semestrales']->count() : 0 }})
            </span>
            <svg x-show="!open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
            <svg x-show="open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"></path></svg>
        </button>
        <div x-show="open" class="space-y-4" x-transition>
            @if(isset($analisisData['semestrales']) && $analisisData['semestrales']->count() > 0)
                @foreach($analisisData['semestrales'] as $analisis)
                    <div class="border border-gray-200 rounded-lg p-4 bg-white">
                        <div class="flex justify-between items-start mb-3">
                            @if($analisis->fechaanalisis)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
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
                            <!-- Marcadores Virológicos -->
                            <div class="mb-4">
                                <h5 class="text-sm font-semibold text-gray-700 mb-3 border-b border-gray-200 pb-1">
                                    <i class="fas fa-virus mr-1 text-red-500"></i>
                                    Marcadores Virológicos
                                </h5>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500">HBsAg:</span>
                                        <p class="font-medium">
                                            @if($analisis->hbsag)
                                                <span class="text-red-600">
                                                    <i class="fas fa-check-circle mr-1"></i>Positivo
                                                </span>
                                            @else
                                                <span class="text-green-600">
                                                    <i class="fas fa-times-circle mr-1"></i>Negativo
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Anti-HBsAg:</span>
                                        <p class="font-medium">
                                            @if($analisis->antihbsag)
                                                <span class="text-green-600">
                                                    <i class="fas fa-check-circle mr-1"></i>Positivo
                                                </span>
                                            @else
                                                <span class="text-gray-600">
                                                    <i class="fas fa-times-circle mr-1"></i>Negativo
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                    @if($analisis->valorantihbsag)
                                        <div>
                                            <span class="text-gray-500">Valor Anti-HBsAg:</span>
                                            <p class="font-medium">{{ $analisis->valorantihbsag }}</p>
                                        </div>
                                    @endif
                                    <div>
                                        <span class="text-gray-500">Anti-HCV:</span>
                                        <p class="font-medium">
                                            @if($analisis->antihcv)
                                                <span class="text-red-600">
                                                    <i class="fas fa-check-circle mr-1"></i>Positivo
                                                </span>
                                            @else
                                                <span class="text-green-600">
                                                    <i class="fas fa-times-circle mr-1"></i>Negativo
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Anti-HIV:</span>
                                        <p class="font-medium">
                                            @if($analisis->antihiv)
                                                <span class="text-red-600">
                                                    <i class="fas fa-check-circle mr-1"></i>Positivo
                                                </span>
                                            @else
                                                <span class="text-green-600">
                                                    <i class="fas fa-times-circle mr-1"></i>Negativo
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Anti-Core:</span>
                                        <p class="font-medium">
                                            @if($analisis->anticore)
                                                <span class="text-red-600">
                                                    <i class="fas fa-check-circle mr-1"></i>Positivo
                                                </span>
                                            @else
                                                <span class="text-green-600">
                                                    <i class="fas fa-times-circle mr-1"></i>Negativo
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Seguimiento Metabólico -->
                            <div>
                                <h5 class="text-sm font-semibold text-gray-700 mb-3 border-b border-gray-200 pb-1">
                                    <i class="fas fa-chart-line mr-1 text-blue-500"></i>
                                    Seguimiento Metabólico
                                </h5>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500">PTH:</span>
                                        <p class="font-medium">{{ $analisis->pth ?? '0' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Ferritina:</span>
                                        <p class="font-medium">{{ $analisis->ferritina ?? '0' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Ferremia:</span>
                                        <p class="font-medium">{{ $analisis->ferremia ?? '0' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-vials text-gray-300 text-4xl mb-4"></i>
                    <p class="text-lg font-medium text-gray-400 mb-2">No hay análisis semestrales registrados</p>
                    <p class="text-sm">Los análisis semestrales aparecerán aquí una vez que se registren.</p>
                </div>
            @endif
        </div>
    </div>
</div>
