<!-- Medicaciones del Paciente -->
<div class="space-y-6">
    <!-- Formulario para nueva Medicación -->
    <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-pills mr-2 text-green-600"></i>
            Registrar Nueva Medicación
        </h4>
        <form method="POST" action="{{ route('medicaciones-pacientes.store', $paciente->id) }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Fecha de Medicación
                    </label>
                    <input 
                        type="date" 
                        name="fechamedicacion" 
                        value="{{ date('Y-m-d') }}" 
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                        required>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-capsules mr-1"></i>
                        Medicación
                    </label>
                    <select name="id_medicacion" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="">Seleccionar medicación...</option>
                        @if(isset($medicaciones))
                            @foreach($medicaciones as $medicacion)
                                <option value="{{ $medicacion->id }}">{{ $medicacion->nombre }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-balance-scale mr-1"></i>
                        Cantidad
                    </label>
                    <input 
                        type="number" 
                        step="0.01" 
                        name="cantidad" 
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="0.00">
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-sticky-note mr-1"></i>
                        Observaciones
                    </label>
                    <input 
                        type="text" 
                        name="observaciones" 
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="Observaciones opcionales">
                </div>
            </div>
            
            <div class="flex justify-end">
                <button 
                    type="submit"
                    class="px-6 py-2 rounded bg-green-600 hover:bg-green-700 text-white font-bold transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <i class="fas fa-save mr-2"></i>
                    Guardar Medicación
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Medicaciones -->
    <div class="bg-white border border-gray-200 rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-list mr-2 text-gray-600"></i>
                Historial de Medicaciones
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    {{ $paciente->medicacionesPacientes ? $paciente->medicacionesPacientes->count() : 0 }} registros
                </span>
            </h4>
        </div>
        
        <div class="p-6">
            @if($paciente->medicacionesPacientes && $paciente->medicacionesPacientes->count() > 0)
                <div class="space-y-4">
                    @foreach($paciente->medicacionesPacientes as $medicacionPaciente)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-pills mr-1"></i>
                                        {{ $medicacionPaciente->medicacion->nombre ?? 'Medicación no especificada' }}
                                    </span>
                                    
                                    @if($medicacionPaciente->medicacion && $medicacionPaciente->medicacion->tipoMedicacion)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-tag mr-1"></i>
                                            {{ $medicacionPaciente->medicacion->tipoMedicacion->nombre }}
                                        </span>
                                    @endif
                                </div>
                                
                                @if($medicacionPaciente->fechamedicacion)
                                    <span class="text-sm text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ \Carbon\Carbon::parse($medicacionPaciente->fechamedicacion)->format('d/m/Y H:i') }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                @if($medicacionPaciente->cantidad)
                                    <div class="flex items-center">
                                        <i class="fas fa-balance-scale text-gray-400 mr-2"></i>
                                        <span class="text-gray-500">Cantidad:</span>
                                        <span class="ml-1 font-medium">{{ $medicacionPaciente->cantidad }}</span>
                                    </div>
                                @endif
                                
                                @if($medicacionPaciente->medicacion && $medicacionPaciente->medicacion->presentacion)
                                    <div class="flex items-center">
                                        <i class="fas fa-box text-gray-400 mr-2"></i>
                                        <span class="text-gray-500">Presentación:</span>
                                        <span class="ml-1 font-medium">{{ $medicacionPaciente->medicacion->presentacion }}</span>
                                    </div>
                                @endif
                                
                                @if($medicacionPaciente->medicacion && $medicacionPaciente->medicacion->concentracion)
                                    <div class="flex items-center">
                                        <i class="fas fa-flask text-gray-400 mr-2"></i>
                                        <span class="text-gray-500">Concentración:</span>
                                        <span class="ml-1 font-medium">{{ $medicacionPaciente->medicacion->concentracion }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            @if($medicacionPaciente->observaciones)
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <span class="text-gray-500 font-medium text-sm">
                                        <i class="fas fa-sticky-note mr-1"></i>
                                        Observaciones:
                                    </span>
                                    <p class="text-sm text-gray-700 mt-1">{{ $medicacionPaciente->observaciones }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-pills text-gray-400 text-3xl"></i>
                    </div>
                    <p class="text-lg font-medium text-gray-400 mb-2">No hay medicaciones registradas</p>
                    <p class="text-sm text-gray-500">Las medicaciones del paciente aparecerán aquí una vez que se registren.</p>
                </div>
            @endif
        </div>
    </div>
</div>
