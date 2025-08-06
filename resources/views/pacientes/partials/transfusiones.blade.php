<!-- Transfusiones del Paciente -->
<div class="space-y-6">
    <!-- Formulario para nueva Transfusión -->
    <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-tint mr-2 text-red-600"></i>
            Registrar Nueva Transfusión
        </h4>
        <form method="POST" action="{{ route('transfusiones.store', $paciente->id) }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Fecha de Transfusión
                    </label>
                    <input type="date" name="fechatransfusion" value="{{ now()->format('Y-m-d') }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" required>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    <i class="fas fa-sticky-note mr-1"></i>
                    Observaciones
                </label>
                <textarea name="observaciones" rows="4" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Detalles de la transfusión: tipo de sangre, cantidad, motivo, reacciones, etc..."></textarea>
            </div>
            
            <div class="flex justify-end">
                <button 
                    type="submit"
                    class="px-6 py-2 rounded bg-red-600 hover:bg-red-700 text-white font-bold transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500">
                    <i class="fas fa-save mr-2"></i>
                    Guardar Transfusión
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Transfusiones -->
    <div class="bg-white border border-gray-200 rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-list mr-2 text-gray-600"></i>
                Historial de Transfusiones
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    {{ $paciente->transfusiones ? $paciente->transfusiones->count() : 0 }} registros
                </span>
            </h4>
        </div>
        
        <div class="p-6">
            @if($paciente->transfusiones && $paciente->transfusiones->count() > 0)
                <div class="space-y-4">
                    @foreach($paciente->transfusiones as $transfusion)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-tint mr-1"></i>
                                        Transfusión
                                    </span>
                                </div>
                                
                                @if($transfusion->fechatransfusion)
                                    <span class="text-sm text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ \Carbon\Carbon::parse($transfusion->fechatransfusion)->format('d/m/Y') }}
                                    </span>
                                @endif
                            </div>
                            
                            @if($transfusion->fechatransfusion)
                                <div class="text-sm text-gray-600 mb-2">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ \Carbon\Carbon::parse($transfusion->fechatransfusion)->diffForHumans() }}
                                </div>
                            @endif
                            
                            @if($transfusion->observaciones)
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <span class="text-gray-500 font-medium text-sm">
                                        <i class="fas fa-file-medical mr-1"></i>
                                        Detalles:
                                    </span>
                                    <p class="text-sm text-gray-700 mt-1">{{ $transfusion->observaciones }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-tint text-gray-400 text-3xl"></i>
                    </div>
                    <p class="text-lg font-medium text-gray-400 mb-2">No hay transfusiones registradas</p>
                    <p class="text-sm text-gray-500">Las transfusiones del paciente aparecerán aquí una vez que se registren.</p>
                </div>
            @endif
        </div>
    </div>
</div>
