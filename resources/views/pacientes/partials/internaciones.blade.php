<!-- Internaciones del Paciente -->
<div class="space-y-6">
    <!-- Formulario para nueva Internación -->
    <div class="bg-gradient-to-r from-orange-50 to-orange-100 border border-orange-200 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-hospital mr-2 text-orange-600"></i>
            Registrar Nueva Internación
        </h4>
        <form method="POST" action="{{ route('internaciones.store', $paciente->id) }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Fecha de Inicio
                    </label>
                    <input type="date" name="fechainiciointernacion" value="{{ now()->format('Y-m-d') }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-calendar-check mr-1"></i>
                        Fecha de Fin
                    </label>
                    <input type="date" name="fechafininternacion" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-stethoscope mr-1"></i>
                        Motivo de Internación
                    </label>
                    <select name="id_motivo_internacion" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                        <option value="">Seleccionar motivo</option>
                        @foreach($motivosInternacion as $motivo)
                            <option value="{{ $motivo->id }}">{{ $motivo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    <i class="fas fa-sticky-note mr-1"></i>
                    Observaciones
                </label>
                <textarea name="observaciones" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="Detalles de la internación, evolución, tratamientos..."></textarea>
            </div>
            
            <div class="flex justify-end">
                <button 
                    type="submit"
                    class="px-6 py-2 rounded bg-orange-600 hover:bg-orange-700 text-white font-bold transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <i class="fas fa-save mr-2"></i>
                    Guardar Internación
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Internaciones -->
    <div class="bg-white border border-gray-200 rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-list mr-2 text-gray-600"></i>
                Historial de Internaciones
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                    {{ $paciente->internaciones ? $paciente->internaciones->count() : 0 }} registros
                </span>
            </h4>
        </div>
        
        <div class="p-6">
            @if($paciente->internaciones && $paciente->internaciones->count() > 0)
                <div class="space-y-4">
                    @foreach($paciente->internaciones as $internacion)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center space-x-3">
                                    @if($internacion->motivoInternacion)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                            <i class="fas fa-stethoscope mr-1"></i>
                                            {{ $internacion->motivoInternacion->nombre }}
                                        </span>
                                    @endif
                                    
                                    @if($internacion->fechafininternacion)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Finalizada
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            En curso
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="text-sm text-gray-500">
                                    @if($internacion->fechainiciointernacion)
                                        <i class="fas fa-sign-in-alt mr-1"></i>
                                        {{ \Carbon\Carbon::parse($internacion->fechainiciointernacion)->format('d/m/Y') }}
                                    @endif
                                    @if($internacion->fechafininternacion)
                                        - <i class="fas fa-sign-out-alt mr-1"></i>
                                        {{ \Carbon\Carbon::parse($internacion->fechafininternacion)->format('d/m/Y') }}
                                    @endif
                                </div>
                            </div>
                            
                            @if($internacion->fechainiciointernacion && $internacion->fechafininternacion)
                                <div class="text-sm text-gray-600 mb-2">
                                    <i class="fas fa-hourglass-half mr-1"></i>
                                    Duración: {{ \Carbon\Carbon::parse($internacion->fechainiciointernacion)->diffInDays(\Carbon\Carbon::parse($internacion->fechafininternacion)) }} días
                                </div>
                            @endif
                            
                            @if($internacion->observaciones)
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <span class="text-gray-500 font-medium text-sm">
                                        <i class="fas fa-file-medical mr-1"></i>
                                        Observaciones:
                                    </span>
                                    <p class="text-sm text-gray-700 mt-1">{{ $internacion->observaciones }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-hospital text-gray-400 text-3xl"></i>
                    </div>
                    <p class="text-lg font-medium text-gray-400 mb-2">No hay internaciones registradas</p>
                    <p class="text-sm text-gray-500">Las internaciones del paciente aparecerán aquí una vez que se registren.</p>
                </div>
            @endif
        </div>
    </div>
</div>
