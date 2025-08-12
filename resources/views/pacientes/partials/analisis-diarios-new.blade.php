<!-- Análisis Diarios con manejo de dos etapas -->
<div x-show="activeTab === 'diarios'" x-transition class="space-y-4" x-data="{
    showPreForm: true,
    showPostForm: false,
    pendientes: [],
    loadPendientes() {
        fetch(`{{ route('analisis-diarios.pendientes', $paciente->id) }}`)
            .then(response => response.json())
            .then(data => {
                this.pendientes = data;
                this.showPostForm = data.length > 0;
            });
    }
}" x-init="loadPendientes()">

    <!-- Sección PRE-Diálisis -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-md font-semibold text-gray-800">
                <i class="fas fa-arrow-right mr-2 text-blue-600"></i>
                Datos PRE-Diálisis
            </h4>
            <button @click="showPreForm = !showPreForm" 
                    class="text-blue-600 hover:text-blue-800 font-medium">
                <span x-text="showPreForm ? 'Ocultar' : 'Mostrar'"></span>
                <i class="fas" :class="showPreForm ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
            </button>
        </div>
        
        <div x-show="showPreForm" x-transition>
            <form method="POST" action="{{ route('analisis-diarios.store-pre', $paciente->id) }}">
                @csrf
                <input type="hidden" name="fechaanalisis" value="{{ now()->format('Y-m-d') }}">
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            <i class="fas fa-weight mr-1 text-blue-600"></i>
                            Peso Pre (kg) *
                        </label>
                        <input type="number" step="0.01" name="pesopre" required 
                               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            <i class="fas fa-heartbeat mr-1 text-red-600"></i>
                            TAS Pre *
                        </label>
                        <input type="number" name="taspre" required 
                               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            <i class="fas fa-heartbeat mr-1 text-red-600"></i>
                            TAD Pre *
                        </label>
                        <input type="number" name="tadpre" required 
                               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            <i class="fas fa-filter mr-1 text-green-600"></i>
                            Tipo de Filtro *
                        </label>
                        <select name="id_tipofiltro" required 
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccione...</option>
                            @foreach($tiposFiltros as $filtro)
                                <option value="{{ $filtro->id }}">{{ $filtro->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            <i class="fas fa-calculator mr-1 text-purple-600"></i>
                            Rel. Peso Seco/Peso Pre *
                        </label>
                        <input type="number" step="0.01" name="relpesosecopesopre" required 
                               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            <i class="fas fa-clock mr-1 text-orange-600"></i>
                            Interdiálitico *
                        </label>
                        <input type="number" step="0.01" name="interdialitico" required 
                               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" 
                            class="px-6 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white font-bold transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Guardar Datos PRE-Diálisis
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sección POST-Diálisis -->
    <div x-show="showPostForm" class="bg-green-50 border border-green-200 rounded-lg p-6">
        <h4 class="text-md font-semibold text-gray-800 mb-4">
            <i class="fas fa-arrow-left mr-2 text-green-600"></i>
            Completar Datos POST-Diálisis
        </h4>
        
        <div x-show="pendientes.length > 0" class="space-y-4">
            <template x-for="analisis in pendientes" :key="analisis.id">
                <div class="border border-green-300 rounded-lg p-4 bg-white">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium text-gray-600">
                            <i class="fas fa-calendar mr-1"></i>
                            Fecha: <span x-text="new Date(analisis.fechaanalisis).toLocaleDateString('es-ES')"></span>
                        </span>
                        <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-medium">
                            Pendiente completar
                        </span>
                    </div>
                    
                    <form :action="`{{ url('/analisis-diarios') }}/${analisis.id}/completar`" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2">
                                    <i class="fas fa-weight mr-1 text-green-600"></i>
                                    Peso Post (kg) *
                                </label>
                                <input type="number" step="0.01" name="pesopost" required 
                                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2">
                                    <i class="fas fa-heartbeat mr-1 text-red-600"></i>
                                    TAS Post *
                                </label>
                                <input type="number" name="taspos" required 
                                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2">
                                    <i class="fas fa-heartbeat mr-1 text-red-600"></i>
                                    TAD Post *
                                </label>
                                <input type="number" name="tadpos" required 
                                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="px-6 py-2 rounded bg-green-600 hover:bg-green-700 text-white font-bold transition-colors duration-200">
                                <i class="fas fa-check mr-2"></i>
                                Completar Análisis
                            </button>
                        </div>
                    </form>
                </div>
            </template>
        </div>
        
        <div x-show="pendientes.length === 0" class="text-center py-6 text-gray-500">
            <i class="fas fa-check-circle text-gray-300 text-3xl mb-2"></i>
            <p>No hay análisis pendientes de completar</p>
        </div>
    </div>

    <!-- Lista de Análisis Completos -->
    <div x-data="{ open: false }" class="space-y-2">
        <button @click="open = !open"
                class="w-full flex items-center justify-between px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded font-semibold text-gray-700 focus:outline-none transition-colors duration-200">
            <span>
                <span x-show="!open">Mostrar</span>
                <span x-show="open">Ocultar</span>
                Historial de Análisis Diarios ({{ isset($analisisData['diarios']) ? $analisisData['diarios']->count() : 0 }})
            </span>
            <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
            </svg>
            <svg x-show="open" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"></path>
            </svg>
        </button>
        
        <div x-show="open" class="space-y-4" x-transition>
            @if(isset($analisisData['diarios']) && $analisisData['diarios']->count() > 0)
                @foreach($analisisData['diarios'] as $analisis)
                    <div class="border border-gray-200 rounded-lg p-4 bg-white">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center space-x-2">
                                @if($analisis->fechaanalisis)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ \Carbon\Carbon::parse($analisis->fechaanalisis)->format('d/m/Y') }}
                                    </span>
                                @endif
                                @if(isset($analisis->estado))
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                        {{ $analisis->estado === 'completo' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                        <i class="fas {{ $analisis->estado === 'completo' ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                                        {{ $analisis->estado === 'completo' ? 'Completo' : 'Pre-diálisis' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-3 md:grid-cols-5 gap-4 text-sm">
                            <!-- Datos PRE siempre visibles -->
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
                                <span class="text-gray-500">Interdiálitico:</span>
                                <p class="font-medium">{{ $analisis->interdialitico ? $analisis->interdialitico . ' kg' : 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Tipo Filtro:</span>
                                <p class="font-medium">{{ $analisis->tipoFiltro->nombre ?? 'N/A' }}</p>
                            </div>
                            
                            <!-- Datos POST solo si están completos -->
                            @if(isset($analisis->estado) && $analisis->estado === 'completo')
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
                            @endif
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
