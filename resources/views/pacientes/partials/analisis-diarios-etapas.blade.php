<!-- Análisis Diarios en Dos Etapas -->
<div x-show="activeTab === 'diarios'" x-transition class="space-y-6">
    
    @php
        // Verificar si hay análisis incompleto para hoy
        $analisisHoyIncompleto = $paciente->analisisDiarios()
            ->whereDate('fechaanalisis', now()->format('Y-m-d'))
            ->where('estado', '!=', 'completo')
            ->first();
    @endphp
    
    <!-- Formulario para Pre-Diálisis (solo si no hay análisis incompleto para hoy) -->
    @if(!$analisisHoyIncompleto)
    <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-clock mr-2 text-blue-600"></i>
            Datos Pre-Diálisis
            <span class="text-sm font-normal text-gray-600 ml-2">(Primera carga del día)</span>
        </h4>
        
        <form method="POST" action="{{ route('analisis-diarios.store-pre-dialisis', $paciente->id) }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-calendar mr-1"></i>
                        Fecha
                    </label>
                    <input type="date" name="fechaanalisis" value="{{ now()->format('Y-m-d') }}" 
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-weight mr-1"></i>
                        Peso Pre (kg)
                    </label>
                    <input type="number" step="0.01" name="pesopre" min="0" max="500"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-heartbeat mr-1"></i>
                        TAS Pre
                    </label>
                    <input type="number" name="taspre" min="0" max="300"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-heartbeat mr-1"></i>
                        TAD Pre
                    </label>
                    <input type="number" name="tadpre" min="0" max="200"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-filter mr-1"></i>
                        Tipo de Filtro
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
                        <i class="fas fa-percentage mr-1"></i>
                        Rel. Peso Seco/Pre
                    </label>
                    <input type="number" step="0.01" name="relpesosecopesopre" min="0" max="100"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-clock mr-1"></i>
                        Interdiálitico
                    </label>
                    <input type="number" step="0.01" name="interdialitico" min="0" max="10"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit"
                        class="px-6 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white font-bold transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i>
                    Guardar Pre-Diálisis
                </button>
            </div>
        </form>
    </div>
    @else
    <!-- Mensaje cuando ya hay análisis incompleto para hoy -->
    <div class="bg-gradient-to-r from-amber-50 to-amber-100 border border-amber-200 rounded-lg p-6">
        <div class="flex items-start justify-between">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-amber-600 text-2xl mr-4"></i>
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">
                        Análisis del día ya iniciado
                    </h4>
                    <p class="text-gray-700 mb-2">
                        Ya existe un análisis incompleto para hoy ({{ now()->format('d/m/Y') }}). 
                    </p>
                    @if($analisisHoyIncompleto)
                    <div class="text-sm text-gray-600 mb-2">
                        <strong>Datos registrados:</strong> 
                        Peso Pre: {{ $analisisHoyIncompleto->pesopre }}kg, 
                        TA Pre: {{ $analisisHoyIncompleto->taspre }}/{{ $analisisHoyIncompleto->tadpre }}
                        @if($analisisHoyIncompleto->tipoFiltro)
                        , Filtro: {{ $analisisHoyIncompleto->tipoFiltro->nombre }}
                        @endif
                    </div>
                    @endif
                    <p class="text-sm text-gray-600">
                        Complete el análisis pendiente en la sección de abajo.
                    </p>
                </div>
            </div>
            
            <button onclick="mostrarFormularioOtraFecha()" 
                    class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded font-medium transition-colors duration-200 whitespace-nowrap">
                <i class="fas fa-plus mr-2"></i>
                Crear para otra fecha
            </button>
        </div>
        
        <!-- Formulario oculto para crear análisis en otra fecha -->
        <div id="formularioOtraFecha" class="hidden mt-6 pt-6 border-t border-amber-300">
            <h5 class="text-md font-semibold text-gray-800 mb-4">
                <i class="fas fa-calendar-plus mr-2"></i>
                Nuevo Análisis para Fecha Específica
            </h5>
            
            <form method="POST" action="{{ route('analisis-diarios.store-pre-dialisis', $paciente->id) }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            <i class="fas fa-calendar mr-1"></i>
                            Fecha *
                        </label>
                        <input type="date" name="fechaanalisis" max="{{ now()->format('Y-m-d') }}"
                               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            <i class="fas fa-weight mr-1"></i>
                            Peso Pre (kg) *
                        </label>
                        <input type="number" step="0.01" name="pesopre" min="0" max="500"
                               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            <i class="fas fa-heartbeat mr-1"></i>
                            TAS Pre *
                        </label>
                        <input type="number" name="taspre" min="0" max="300"
                               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            <i class="fas fa-heartbeat mr-1"></i>
                            TAD Pre *
                        </label>
                        <input type="number" name="tadpre" min="0" max="200"
                               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            <i class="fas fa-filter mr-1"></i>
                            Tipo de Filtro *
                        </label>
                        <select name="id_tipofiltro" required 
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <option value="">Seleccione...</option>
                            @foreach($tiposFiltros as $filtro)
                                <option value="{{ $filtro->id }}">{{ $filtro->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            <i class="fas fa-percentage mr-1"></i>
                            Rel. Peso Seco/Pre
                        </label>
                        <input type="number" step="0.01" name="relpesosecopesopre" min="0" max="100"
                               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 text-sm font-semibold mb-2">
                            <i class="fas fa-clock mr-1"></i>
                            Interdiálitico
                        </label>
                        <input type="number" step="0.01" name="interdialitico" min="0" max="10"
                               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>
                
                <div class="flex justify-between">
                    <button type="button" onclick="ocultarFormularioOtraFecha()"
                            class="px-4 py-2 rounded bg-gray-500 hover:bg-gray-600 text-white font-medium transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </button>
                    
                    <button type="submit"
                            class="px-6 py-2 rounded bg-amber-600 hover:bg-amber-700 text-white font-bold transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-amber-500">
                        <i class="fas fa-save mr-2"></i>
                        Guardar Pre-Diálisis
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
    
    <!-- Análisis Pendientes de Completar -->
    @php
        $analisisPendientes = $paciente->analisisDiarios()
            ->where('estado', '!=', 'completo')
            ->with(['tipoFiltro'])
            ->orderBy('fechaanalisis', 'desc')
            ->take(5)
            ->get();
    @endphp
    
    @if($analisisPendientes->count() > 0)
    <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-exclamation-triangle mr-2 text-yellow-600"></i>
            Análisis Pendientes de Completar
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 ml-2">
                {{ $analisisPendientes->count() }} pendientes
            </span>
        </h4>
        
        <div class="space-y-3">
            @foreach($analisisPendientes as $pendiente)
            <div class="bg-white border border-yellow-300 rounded-lg p-4">
                <div class="flex justify-between items-center mb-3">
                    <div class="flex items-center space-x-3">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ \Carbon\Carbon::parse($pendiente->fechaanalisis)->format('d/m/Y') }}
                        </span>
                        <span class="text-sm text-gray-600">
                            Peso Pre: <strong>{{ $pendiente->pesopre }} kg</strong> | 
                            TA Pre: <strong>{{ $pendiente->taspre }}/{{ $pendiente->tadpre }}</strong>
                        </span>
                    </div>
                    
                    <button onclick="mostrarFormularioCompletar('{{ $pendiente->id }}', '{{ $pendiente->fechaanalisis }}')"
                            class="px-3 py-1 text-sm bg-yellow-600 hover:bg-yellow-700 text-white rounded font-medium transition-colors duration-200">
                        <i class="fas fa-plus mr-1"></i>
                        Completar
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    <!-- Formulario para Post-Diálisis (inicialmente oculto) -->
    <div id="formularioPostDialisis" class="hidden bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-check-circle mr-2 text-green-600"></i>
            Completar Análisis - Datos Post-Diálisis
            <span class="text-sm font-normal text-gray-600 ml-2">(Segunda carga del día)</span>
        </h4>
        
        <form method="POST" action="{{ route('analisis-diarios.store-post-dialisis', $paciente->id) }}" id="formPostDialisis">
            @csrf
            <input type="hidden" name="fechaanalisis" id="fechaPostDialisis">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-weight mr-1"></i>
                        Peso Post (kg)
                    </label>
                    <input type="number" step="0.01" name="pesopost" min="0" max="500"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-heartbeat mr-1"></i>
                        TAS Post
                    </label>
                    <input type="number" name="taspos" min="0" max="300"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-heartbeat mr-1"></i>
                        TAD Post
                    </label>
                    <input type="number" name="tadpos" min="0" max="200"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
                </div>
                
            </div>
            
            <div class="flex justify-between">
                <button type="button" onclick="ocultarFormularioCompletar()"
                        class="px-4 py-2 rounded bg-gray-500 hover:bg-gray-600 text-white font-medium transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </button>
                
                <button type="submit"
                        class="px-6 py-2 rounded bg-green-600 hover:bg-green-700 text-white font-bold transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <i class="fas fa-check mr-2"></i>
                    Completar Análisis
                </button>
            </div>
        </form>
    </div>
    
    <!-- Lista de Análisis Completos -->
    <div x-data="{ open: false }" class="space-y-2">
        <button 
            @click="open = !open"
            class="w-full flex items-center justify-between px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded font-semibold text-gray-700 focus:outline-none transition-colors duration-200"
            type="button">
            <span>
                <span x-show="!open">Mostrar</span>
                <span x-show="open">Ocultar</span>
                Análisis Diarios Completos ({{ isset($analisisData['diarios']) ? $analisisData['diarios']->where('estado', 'completo')->count() : 0 }})
            </span>
            <svg x-show="!open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
            </svg>
            <svg x-show="open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"></path>
            </svg>
        </button>
        
        <div x-show="open" class="space-y-4" x-transition>
            @if(isset($analisisData['diarios']) && $analisisData['diarios']->count() > 0)
                @foreach($analisisData['diarios']->where('estado', 'completo') as $analisis)
                    <div class="border border-gray-200 rounded-lg p-4 bg-white">
                        <div class="flex justify-between items-start mb-3">
                            @if($analisis->fechaanalisis)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ \Carbon\Carbon::parse($analisis->fechaanalisis)->format('d/m/Y') }}
                                </span>
                            @endif
                            
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i>
                                Completo
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Peso Pre:</span>
                                <p class="font-medium">{{ $analisis->pesopre ? $analisis->pesopre . ' kg' : 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Peso Post:</span>
                                <p class="font-medium">{{ $analisis->pesopost ? $analisis->pesopost . ' kg' : 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">TA Pre:</span>
                                <p class="font-medium">{{ $analisis->taspre && $analisis->tadpre ? $analisis->taspre . '/' . $analisis->tadpre : 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">TA Post:</span>
                                <p class="font-medium">{{ $analisis->taspos && $analisis->tadpos ? $analisis->taspos . '/' . $analisis->tadpos : 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Filtro:</span>
                                <p class="font-medium">{{ $analisis->tipoFiltro ? $analisis->tipoFiltro->nombre : 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500">Interdiálitico:</span>
                                <p class="font-medium">{{ $analisis->interdialitico ?? 'N/A' }}</p>
                            </div>
                        </div>
                        
                        @if($analisis->observaciones)
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <span class="text-gray-500 font-medium text-sm">
                                    <i class="fas fa-file-alt mr-1"></i>
                                    Observaciones:
                                </span>
                                <p class="text-sm text-gray-700 mt-1">{{ $analisis->observaciones }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-chart-line text-gray-400 text-3xl"></i>
                    </div>
                    <p class="text-lg font-medium text-gray-400 mb-2">No hay análisis diarios registrados</p>
                    <p class="text-sm text-gray-500">Los análisis diarios aparecerán aquí una vez que se registren.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function mostrarFormularioCompletar(analisisId, fecha) {
    document.getElementById('formularioPostDialisis').classList.remove('hidden');
    document.getElementById('fechaPostDialisis').value = fecha;
    
    // Scroll suave hacia el formulario
    document.getElementById('formularioPostDialisis').scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

function ocultarFormularioCompletar() {
    document.getElementById('formularioPostDialisis').classList.add('hidden');
    document.getElementById('formPostDialisis').reset();
}

function mostrarFormularioOtraFecha() {
    document.getElementById('formularioOtraFecha').classList.remove('hidden');
    
    // Scroll suave hacia el formulario
    document.getElementById('formularioOtraFecha').scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

function ocultarFormularioOtraFecha() {
    document.getElementById('formularioOtraFecha').classList.add('hidden');
}
</script>
