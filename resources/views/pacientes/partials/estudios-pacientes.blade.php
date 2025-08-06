<!-- Estudios del Paciente -->
<div class="space-y-6">
    <!-- Formulario para nuevo Estudio -->
    <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-microscope mr-2 text-green-600"></i>
            Registrar Nuevo Estudio
        </h4>
        <form method="POST" action="{{ route('estudios-pacientes.store', $paciente->id) }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Fecha del Estudio
                    </label>
                    <input type="date" name="fechaestudio" value="{{ now()->format('Y-m-d') }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-list-alt mr-1"></i>
                        Tipo de Estudio
                    </label>
                    <select name="id_estudio" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="">Seleccionar estudio</option>
                        @foreach($estudios as $estudio)
                            <option value="{{ $estudio->id }}">{{ $estudio->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    <i class="fas fa-sticky-note mr-1"></i>
                    Observaciones
                </label>
                <textarea name="observaciones" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Detalles del estudio, resultados, observaciones..."></textarea>
            </div>
            
            <div class="flex justify-end">
                <button 
                    type="submit"
                    class="px-6 py-2 rounded bg-green-600 hover:bg-green-700 text-white font-bold transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <i class="fas fa-save mr-2"></i>
                    Guardar Estudio
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Estudios -->
    <div class="bg-white border border-gray-200 rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-list mr-2 text-gray-600"></i>
                Historial de Estudios
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    {{ $paciente->estudiosPacientes ? $paciente->estudiosPacientes->count() : 0 }} registros
                </span>
            </h4>
        </div>
        
        <div class="p-6">
            @if($paciente->estudiosPacientes && $paciente->estudiosPacientes->count() > 0)
                <div class="space-y-4">
                    @foreach($paciente->estudiosPacientes as $estudioPaciente)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center space-x-3">
                                    @if($estudioPaciente->estudio)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-microscope mr-1"></i>
                                            {{ $estudioPaciente->estudio->nombre }}
                                        </span>
                                    @endif
                                </div>
                                
                                @if($estudioPaciente->fechaestudio)
                                    <span class="text-sm text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ \Carbon\Carbon::parse($estudioPaciente->fechaestudio)->format('d/m/Y') }}
                                    </span>
                                @endif
                            </div>
                            
                            @if($estudioPaciente->observaciones)
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <span class="text-gray-500 font-medium text-sm">
                                        <i class="fas fa-file-alt mr-1"></i>
                                        Observaciones:
                                    </span>
                                    <p class="text-sm text-gray-700 mt-1">{{ $estudioPaciente->observaciones }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-microscope text-gray-400 text-3xl"></i>
                    </div>
                    <p class="text-lg font-medium text-gray-400 mb-2">No hay estudios registrados</p>
                    <p class="text-sm text-gray-500">Los estudios del paciente aparecerán aquí una vez que se registren.</p>
                </div>
            @endif
        </div>
    </div>
</div>
