<!-- Vacunas del Paciente -->
<div class="space-y-6">
    <!-- Formulario para nueva Vacuna -->
    <div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-syringe mr-2 text-purple-600"></i>
            Registrar Nueva Vacuna
        </h4>
        <form method="POST" action="{{ route('vacunas-pacientes.store', $paciente->id) }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Fecha de Vacunación
                    </label>
                    <input 
                        type="date" 
                        name="fechavacuna" 
                        value="{{ date('Y-m-d') }}" 
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                        required>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-shield-alt mr-1"></i>
                        Vacuna
                    </label>
                    <select name="id_vacuna" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                        <option value="">Seleccionar vacuna...</option>
                        @if(isset($vacunas))
                            @foreach($vacunas as $vacuna)
                                <option value="{{ $vacuna->id }}">{{ $vacuna->nombre }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-sticky-note mr-1"></i>
                        Observaciones
                    </label>
                    <input 
                        type="text" 
                        name="observaciones" 
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Observaciones opcionales">
                </div>
            </div>
            
            <div class="flex justify-end">
                <button 
                    type="submit"
                    class="px-6 py-2 rounded bg-purple-600 hover:bg-purple-700 text-white font-bold transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <i class="fas fa-save mr-2"></i>
                    Guardar Vacuna
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Vacunas -->
    <div class="bg-white border border-gray-200 rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-list mr-2 text-gray-600"></i>
                Historial de Vacunación
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                    {{ $paciente->vacunasPacientes ? $paciente->vacunasPacientes->count() : 0 }} registros
                </span>
            </h4>
        </div>
        
        <div class="p-6">
            @if($paciente->vacunasPacientes && $paciente->vacunasPacientes->count() > 0)
                <div class="space-y-6">
                    @foreach($paciente->vacunasPacientes as $vacunaPaciente)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                            <!-- Encabezado de la Vacuna -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-syringe mr-1"></i>
                                        {{ $vacunaPaciente->vacuna->nombre ?? 'Vacuna no especificada' }}
                                    </span>
                                </div>
                                
                                @if($vacunaPaciente->fechavacuna)
                                    <span class="text-sm text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ \Carbon\Carbon::parse($vacunaPaciente->fechavacuna)->format('d/m/Y') }}
                                    </span>
                                @endif
                            </div>
                            
                            @if($vacunaPaciente->observaciones)
                                <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                                    <span class="text-gray-500 font-medium text-sm">
                                        <i class="fas fa-sticky-note mr-1"></i>
                                        Observaciones:
                                    </span>
                                    <p class="text-sm text-gray-700 mt-1">{{ $vacunaPaciente->observaciones }}</p>
                                </div>
                            @endif
                            
                            <!-- Dosis de la Vacuna -->
                            <div class="border-t border-gray-100 pt-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h5 class="text-sm font-semibold text-gray-700">
                                        <i class="fas fa-calendar-check mr-1"></i>
                                        Dosis Administradas
                                    </h5>
                                    
                                    <!-- Formulario inline para agregar nueva dosis -->
                                    <button 
                                        onclick="toggleDosisForm({{ $vacunaPaciente->id }})"
                                        class="text-xs bg-indigo-100 hover:bg-indigo-200 text-indigo-800 px-2 py-1 rounded transition-colors duration-200">
                                        <i class="fas fa-plus mr-1"></i>
                                        Agregar Dosis
                                    </button>
                                </div>
                                
                                <!-- Formulario para nueva dosis (oculto inicialmente) -->
                                <div id="dosisForm{{ $vacunaPaciente->id }}" class="hidden mb-4 p-3 bg-indigo-50 rounded-lg border border-indigo-200">
                                    <form method="POST" action="{{ route('dosis.store', $vacunaPaciente->id) }}">
                                        @csrf
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Fecha</label>
                                                <input 
                                                    type="date" 
                                                    name="fechadosis" 
                                                    value="{{ date('Y-m-d') }}" 
                                                    class="w-full text-xs border rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                                    required>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Número</label>
                                                <input 
                                                    type="number" 
                                                    name="numero" 
                                                    min="1"
                                                    value="{{ ($vacunaPaciente->dosis ? $vacunaPaciente->dosis->count() : 0) + 1 }}"
                                                    class="w-full text-xs border rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                                    required>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Cantidad</label>
                                                <input 
                                                    type="number" 
                                                    step="0.01" 
                                                    name="cantidad" 
                                                    class="w-full text-xs border rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                                    placeholder="0.5">
                                            </div>
                                            <div class="flex items-end">
                                                <button 
                                                    type="submit"
                                                    class="w-full text-xs bg-indigo-600 hover:bg-indigo-700 text-white px-2 py-1 rounded transition-colors duration-200">
                                                    <i class="fas fa-save mr-1"></i>
                                                    Guardar
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                
                                <!-- Lista de dosis existentes -->
                                @if($vacunaPaciente->dosis && $vacunaPaciente->dosis->count() > 0)
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                        @foreach($vacunaPaciente->dosis->sortBy('numero') as $dosis)
                                            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-3">
                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="text-sm font-medium text-indigo-800">
                                                        <i class="fas fa-hashtag mr-1"></i>
                                                        Dosis {{ $dosis->numero }}
                                                    </span>
                                                    @if($dosis->fechadosis)
                                                        <span class="text-xs text-gray-500">
                                                            {{ \Carbon\Carbon::parse($dosis->fechadosis)->format('d/m/Y') }}
                                                        </span>
                                                    @endif
                                                </div>
                                                
                                                @if($dosis->cantidad)
                                                    <div class="text-xs text-gray-600">
                                                        <i class="fas fa-balance-scale mr-1"></i>
                                                        Cantidad: <span class="font-medium">{{ $dosis->cantidad }} ml</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4 text-gray-500">
                                        <i class="fas fa-calendar-times text-gray-300 text-2xl mb-2"></i>
                                        <p class="text-sm">No hay dosis registradas para esta vacuna</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-syringe text-gray-400 text-3xl"></i>
                    </div>
                    <p class="text-lg font-medium text-gray-400 mb-2">No hay vacunas registradas</p>
                    <p class="text-sm text-gray-500">Las vacunas del paciente aparecerán aquí una vez que se registren.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleDosisForm(vacunaId) {
    const form = document.getElementById('dosisForm' + vacunaId);
    if (form.classList.contains('hidden')) {
        form.classList.remove('hidden');
    } else {
        form.classList.add('hidden');
    }
}
</script>
