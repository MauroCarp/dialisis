<!-- Análisis Diarios -->
<div x-show="activeTab === 'diarios'" x-transition class="space-y-4">
    <!-- Formulario para nuevo Análisis Diario (siempre visible arriba de los registros) -->
    <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-6">
        <h4 class="text-md font-semibold text-gray-800 mb-4">
            <i class="fas fa-calendar-day mr-2 text-teal-600"></i>
            Nuevo Análisis Diario
        </h4>
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
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Interdiálitico</label>
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
                    class="px-4 py-2 rounded bg-teal-600 hover:bg-teal-700 text-white font-bold transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Guardar
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Análisis Diarios -->
    <div x-data="{ open: false }" class="space-y-2">
        <button 
            @click="open = !open"
            class="w-full flex items-center justify-between px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded font-semibold text-gray-700 focus:outline-none transition-colors duration-200"
            type="button">
            <span>
                <span x-show="!open">Mostrar</span>
                <span x-show="open">Ocultar</span>
                Análisis Diarios ({{ isset($analisisData['diarios']) ? $analisisData['diarios']->count() : 0 }})
            </span>
            <svg x-show="!open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
            <svg x-show="open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"></path></svg>
        </button>
        <div x-show="open" class="space-y-4" x-transition>
            @if(isset($analisisData['diarios']) && $analisisData['diarios']->count() > 0)
                @foreach($analisisData['diarios'] as $analisis)
                    <div class="border border-gray-200 rounded-lg p-4 bg-white">
                        <div class="flex justify-between items-start mb-3">
                            @if($analisis->fechaanalisis)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ \Carbon\Carbon::parse($analisis->fechaanalisis)->format('d/m/Y') }}
                                </span>
                            @endif
                        </div>
                        <div class="grid grid-cols-3 md:grid-cols-5 gap-4 text-sm">
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
                    <i class="fas fa-flask text-gray-300 text-4xl mb-4"></i>
                    <p class="text-lg font-medium text-gray-400 mb-2">No hay análisis diarios registrados</p>
                    <p class="text-sm">Los análisis diarios aparecerán aquí una vez que se registren.</p>
                </div>
            @endif
        </div>
    </div>
</div>
