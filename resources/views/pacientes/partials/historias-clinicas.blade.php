<!-- Historias Clínicas Tab -->
<div class="space-y-6">
    <!-- Formulario para nueva Historia Clínica -->
    <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-notes-medical mr-2 text-green-600"></i>
            Nueva Historia Clínica
        </h4>
        <form method="POST" action="{{ isset($esPacienteConsultorio) && $esPacienteConsultorio ? route('historias-clinicas-consultorio.store', $paciente->id) : route('historias-clinicas.store', $paciente->id) }}">
            @csrf

            <input type="hidden" name="fechahistoriaclinica" value="{{ now()->format('Y-m-d H:i:s') }}">
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    <i class="fas fa-sticky-note mr-1"></i>
                    Observaciones
                </label>
                <textarea name="observaciones" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Observaciones adicionales..."></textarea>
            </div>
            
            <div class="flex justify-end">
                <button 
                    type="submit"
                    class="px-6 py-2 rounded bg-teal-600 hover:bg-teal-700 text-white font-bold transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <i class="fas fa-save mr-2"></i>
                    Guardar Historia Clínica
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Historias Clínicas -->
    <div class="bg-white border border-gray-200 rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-history mr-2 text-gray-600"></i>
                Historial de Consultas Médicas
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    @if(isset($esPacienteConsultorio) && $esPacienteConsultorio)
                        {{ $paciente->historiasClinicasConsultorio ? $paciente->historiasClinicasConsultorio->count() : 0 }} registros
                    @else
                        {{ $paciente->historiasClinicas ? $paciente->historiasClinicas->count() : 0 }} registros
                    @endif
                </span>
            </h4>
        </div>
        
        <div class="p-6">
            @php
                $historias = null;
                if(isset($esPacienteConsultorio) && $esPacienteConsultorio) {
                    $historias = $paciente->historiasClinicasConsultorio;
                } else {
                    $historias = $paciente->historiasClinicas;
                }
            @endphp
            
            @if($historias && $historias->count() > 0)
                <div class="space-y-6">
                    @foreach($historias as $historia)
                        <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition-shadow duration-200">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center space-x-3">
                                    @if($historia->fechahistoriaclinica)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-calendar mr-2"></i>
                                            {{ \Carbon\Carbon::parse($historia->fechahistoriaclinica)->format('d/m/Y') }}
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Botón de descarga individual para cada historia -->
                                <div class="flex items-center space-x-2">
                                    @if(isset($esPacienteConsultorio) && $esPacienteConsultorio)
                                        <a href="{{ route('historia-clinica-consultorio.download', ['id' => $historia->id]) }}" 
                                           class="inline-flex items-center px-3 py-1 rounded text-sm bg-blue-500 hover:bg-blue-600 text-white transition-colors duration-200"
                                           title="Descargar Historia Clínica PDF">
                                            <i class="fas fa-download mr-1"></i>
                                            PDF
                                        </a>
                                    @else
                                        <a href="{{ route('historias-clinicas.download', ['id' => $historia->id]) }}" 
                                           class="inline-flex items-center px-3 py-1 rounded text-sm bg-blue-500 hover:bg-blue-600 text-white transition-colors duration-200"
                                           title="Descargar Historia Clínica PDF">
                                            <i class="fas fa-download mr-1"></i>
                                            PDF
                                        </a>
                                    @endif
                                </div>
                            </div>
                            
                            @if($historia->observaciones)
                                <div class="pt-3 border-t border-gray-200">
                                    <h5 class="text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-sticky-note mr-1 text-yellow-500"></i>
                                        Observaciones
                                    </h5>
                                    <p class="text-sm text-gray-700 bg-yellow-50 p-3 rounded">{{ $historia->observaciones }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-notes-medical text-gray-400 text-3xl"></i>
                    </div>
                    <p class="text-lg font-medium text-gray-400 mb-2">No hay historias clínicas registradas</p>
                    <p class="text-sm text-gray-500">Las consultas médicas del paciente aparecerán aquí una vez que se registren.</p>
                </div>
            @endif
        </div>
    </div>
</div>
