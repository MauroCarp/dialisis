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
            
            <div class="flex justify-between">
                <button type="button" onclick="resetearFormularioPreDialisis()"
                        class="px-4 py-2 rounded bg-gray-500 hover:bg-gray-600 text-white font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    <i class="fas fa-plus mr-2"></i>
                    Nuevo Análisis
                </button>
                
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
                    
                    <div class="flex space-x-2">
                        <button onclick="mostrarFormularioCompletar('{{ $pendiente->id }}', '{{ $pendiente->fechaanalisis }}')"
                                class="px-3 py-1 text-sm bg-yellow-600 hover:bg-yellow-700 text-white rounded font-medium transition-colors duration-200">
                            <i class="fas fa-plus mr-1"></i>
                            Completar
                        </button>
                        
                        <button onclick="editarAnalisisPre('{{ $pendiente->id }}')"
                                class="px-3 py-1 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded font-medium transition-colors duration-200">
                            <i class="fas fa-edit mr-1"></i>
                            Editar
                        </button>
                        
                        <button onclick="eliminarAnalisisPre('{{ $pendiente->id }}', '{{ \Carbon\Carbon::parse($pendiente->fechaanalisis)->format('d/m/Y') }}')"
                                class="px-3 py-1 text-sm bg-red-600 hover:bg-red-700 text-white rounded font-medium transition-colors duration-200">
                            <i class="fas fa-trash mr-1"></i>
                            Eliminar
                        </button>
                    </div>
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
    
    <!-- Modal para Editar Análisis PRE-Diálisis -->
    <div id="modalEditarPreDialisis" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" onclick="cerrarModalEditarPre()">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white" onclick="event.stopPropagation()">
            <div class="mt-3">
                <!-- Header del modal -->
                <div class="flex items-center justify-between pb-3 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-edit mr-2 text-orange-600"></i>
                        Editar Análisis Pre-Diálisis
                    </h3>
                    <button type="button" onclick="cerrarModalEditarPre()" 
                            class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Formulario de edición -->
                <form id="formEditarPreDialisis" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    
                    <div class="mt-4 space-y-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">
                                <i class="fas fa-calendar mr-1"></i>
                                Fecha *
                            </label>
                            <input type="date" name="fechaanalisis" id="edit_fechaanalisis" required
                                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">
                                <i class="fas fa-weight mr-1"></i>
                                Peso Pre (kg) *
                            </label>
                            <input type="number" step="0.01" name="pesopre" id="edit_pesopre" min="0" max="500" required
                                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2">
                                    <i class="fas fa-heartbeat mr-1"></i>
                                    TAS Pre *
                                </label>
                                <input type="number" name="taspre" id="edit_taspre" min="0" max="300" required
                                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2">
                                    <i class="fas fa-heartbeat mr-1"></i>
                                    TAD Pre *
                                </label>
                                <input type="number" name="tadpre" id="edit_tadpre" min="0" max="200" required
                                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">
                                <i class="fas fa-filter mr-1"></i>
                                Tipo de Filtro *
                            </label>
                            <select name="id_tipofiltro" id="edit_id_tipofiltro" required
                                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                                <option value="">Seleccione un filtro</option>
                                @foreach($tiposFiltros as $filtro)
                                    <option value="{{ $filtro->id }}">{{ $filtro->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">
                                <i class="fas fa-percentage mr-1"></i>
                                % Rel. Peso Seco/Pre
                            </label>
                            <input type="number" step="0.01" name="relpesosecopesopre" id="edit_relpesosecopesopre" min="0" max="100"
                                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">
                                <i class="fas fa-clock mr-1"></i>
                                Interdiálitico
                            </label>
                            <input type="number" step="0.01" name="interdialitico" id="edit_interdialitico" min="0" max="10"
                                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                    </div>
                    
                    <!-- Botones del modal -->
                    <div class="mt-6 flex justify-between space-x-3">
                        <button type="button" onclick="cerrarModalEditarPre()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </button>
                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>
                            Actualizar Análisis
                        </button>
                    </div>
                </form>
            </div>
        </div>
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

function editarAnalisisPre(analisisId) {
    // Hacer petición AJAX para obtener los datos del análisis
    fetch(`/analisis-diarios/${analisisId}/edit`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {

                // Formatear la fecha para el input date (YYYY-MM-DD)
                let fechaFormateada = data.analisis.fechaanalisis;

                if (fechaFormateada) {
                    // Si la fecha viene en formato 'YYYY-MM-DD HH:MM:SS', extraer solo la parte de la fecha
                    fechaFormateada = fechaFormateada.split('T')[0];
                    // Si la fecha viene en otro formato, convertirla
                    if (fechaFormateada.includes('/')) {
                        // Convertir de DD/MM/YYYY a YYYY-MM-DD
                        const partes = fechaFormateada.split('/');
                        if (partes.length === 3) {
                            fechaFormateada = `${partes[2]}-${partes[1].padStart(2, '0')}-${partes[0].padStart(2, '0')}`;
                        }
                    }
                }
                
                // Llenar el modal con los datos existentes
                document.getElementById('edit_fechaanalisis').value = fechaFormateada;
                document.getElementById('edit_pesopre').value = data.analisis.pesopre;
                document.getElementById('edit_taspre').value = data.analisis.taspre;
                document.getElementById('edit_tadpre').value = data.analisis.tadpre;
                document.getElementById('edit_id_tipofiltro').value = data.analisis.id_tipofiltro;
                document.getElementById('edit_relpesosecopesopre').value = data.analisis.relpesosecopesopre || '';
                document.getElementById('edit_interdialitico').value = data.analisis.interdialitico || '';
                
                // Configurar la acción del formulario
                const form = document.getElementById('formEditarPreDialisis');
                form.action = `/analisis-diarios/${analisisId}`;
                
                // Mostrar el modal
                document.getElementById('modalEditarPreDialisis').classList.remove('hidden');
                
            } else {
                alert('Error al cargar los datos del análisis');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los datos del análisis');
        });
}

function cerrarModalEditarPre() {
    document.getElementById('modalEditarPreDialisis').classList.add('hidden');
    document.getElementById('formEditarPreDialisis').reset();
}

// Cerrar modal con tecla Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('modalEditarPreDialisis');
        if (modal && !modal.classList.contains('hidden')) {
            cerrarModalEditarPre();
        }
    }
});

function eliminarAnalisisPre(analisisId, fecha) {
    if (confirm(`¿Estás seguro de que deseas eliminar el análisis pre-diálisis del ${fecha}? Esta acción no se puede deshacer.`)) {
        // Obtener el token CSRF de forma más robusta
        let csrfToken = null;
        const metaCsrf = document.querySelector('meta[name="csrf-token"]');
        if (metaCsrf) {
            csrfToken = metaCsrf.getAttribute('content');
        } else {
            // Buscar el token en un input hidden si existe
            const csrfInput = document.querySelector('input[name="_token"]');
            if (csrfInput) {
                csrfToken = csrfInput.value;
            }
        }
        
        if (!csrfToken) {
            alert('Error: No se pudo obtener el token de seguridad. Recargue la página e intente nuevamente.');
            return;
        }
        
        // Usar fetch para hacer la petición DELETE de forma asíncrona
        fetch(`/analisis-diarios/${analisisId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mostrar mensaje de éxito (puedes usar alert o un toast personalizado)
                alert(data.message || 'Análisis eliminado correctamente');
                
                // Recargar la página manteniendo la pestaña activa
                const urlActual = new URL(window.location);
                urlActual.searchParams.set('show_tab', 'analisis');
                window.location.href = urlActual.toString();
            } else {
                alert(data.message || 'Error al eliminar el análisis');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el análisis. Por favor, inténtelo de nuevo.');
        });
    }
}

function resetearFormularioPreDialisis() {
    const form = document.getElementById('formPreDialisis');
    if (form) {
        // Resetear la acción del formulario
        form.action = `{{ route('analisis-diarios.store-pre-dialisis', $paciente) }}`;
        
        // Remover campo method si existe
        const methodField = form.querySelector('input[name="_method"]');
        if (methodField) {
            methodField.remove();
        }
        
        // Resetear el botón
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.innerHTML = '<i class="fas fa-save mr-2"></i>Guardar Pre-Diálisis';
            submitButton.classList.remove('bg-orange-600', 'hover:bg-orange-700');
            submitButton.classList.add('bg-blue-600', 'hover:bg-blue-700');
        }
        
        // Limpiar los campos
        form.reset();
        
        // Establecer fecha de hoy
        const fechaInput = form.querySelector('input[name="fechaanalisis"]');
        if (fechaInput) {
            const today = new Date().toISOString().split('T')[0];
            fechaInput.value = today;
        }
    }
}
</script>
