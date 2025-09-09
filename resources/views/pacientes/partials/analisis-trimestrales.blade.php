<!-- Análisis Trimestrales -->
<div x-show="activeTab === 'trimestrales'" x-transition class="space-y-4">
    <!-- Formulario para nuevo Análisis Trimestral -->
    <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-6">
        <h4 class="text-md font-semibold text-gray-800 mb-4">
            <i class="fas fa-calendar-week mr-2 text-teal-600"></i>
            Nuevo Análisis Trimestral
        </h4>
        <form method="POST" action="{{ route('analisis-trimestrales.store', $paciente->id) }}">
            @csrf
            <div class="mb-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Fecha Análisis</label>
                    <input type="date" name="fechaanalisis" value="{{ now()->format('Y-m-d') }}" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Protocolo</label>
                    <input type="text" name="protocolo" class="w-full border rounded px-3 py-2">
                </div>
            </div>
            <div class="mb-4 grid grid-cols-2 md:grid-cols-2 gap-4">
                <!-- Análisis Nutricional -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Albúmina</label>
                    <input type="number" step="0.01" name="albumina" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Colesterol</label>
                    <input type="number" step="0.01" name="colesterol" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Triglicéridos</label>
                    <input type="number" step="0.01" name="trigliseridos" class="w-full border rounded px-3 py-2">
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

    <!-- Lista de Análisis Trimestrales -->
    <div x-data="{ open: false }" class="space-y-2">
        <button 
            @click="open = !open"
            class="w-full flex items-center justify-between px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded font-semibold text-gray-700 focus:outline-none transition-colors duration-200"
            type="button">
            <span>
                <span x-show="!open">Mostrar</span>
                <span x-show="open">Ocultar</span>
                Análisis Trimestrales ({{ isset($analisisData['trimestrales']) ? $analisisData['trimestrales']->count() : 0 }})
            </span>
            <svg x-show="!open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
            <svg x-show="open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"></path></svg>
        </button>
        <div x-show="open" class="space-y-4" x-transition>
            @if(isset($analisisData['trimestrales']) && $analisisData['trimestrales']->count() > 0)
                @foreach($analisisData['trimestrales'] as $analisis)
                    <div class="border border-gray-200 rounded-lg p-4 bg-white">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex space-x-2">
                                @if($analisis->fechaanalisis)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ \Carbon\Carbon::parse($analisis->fechaanalisis)->format('d/m/Y') }}
                                    </span>
                                @endif
                                @if($analisis->protocolo)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-file-alt mr-1"></i>
                                        Protocolo: {{ $analisis->protocolo }}
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Botones de acción -->
                            <div class="flex space-x-2">
                                <!-- Botón de editar -->
                                <button 
                                    onclick="editarAnalisisTrimestral({{ $analisis->id }})"
                                    class="inline-flex items-center px-2 py-1 rounded text-xs bg-purple-500 hover:bg-purple-600 text-white transition-colors duration-200"
                                    title="Editar Análisis Trimestral">
                                    <i class="fas fa-edit mr-1"></i>
                                    Editar
                                </button>
                                
                                <!-- Botón de eliminar -->
                                <form method="POST" action="{{ route('analisis-trimestrales.destroy', $analisis->id) }}" 
                                      class="inline-block"
                                      onsubmit="return confirmarEliminacionForm(event, '¿Está seguro que desea eliminar este análisis trimestral? Esta acción no se puede deshacer.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-2 py-1 rounded text-xs bg-red-500 hover:bg-red-600 text-white transition-colors duration-200"
                                            title="Eliminar Análisis Trimestral">
                                        <i class="fas fa-trash mr-1"></i>
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="mb-2">
                            <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">
                                <i class="fas fa-apple-alt mr-1 text-green-500"></i>
                                Análisis Nutricional
                            </h5>
                            <div class="grid grid-cols-3 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Albúmina:</span>
                                    <p class="font-medium">{{ $analisis->albumina ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Colesterol:</span>
                                    <p class="font-medium">{{ $analisis->colesterol ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Triglicéridos:</span>
                                    <p class="font-medium">{{ $analisis->trigliseridos ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-flask text-gray-300 text-4xl mb-4"></i>
                    <p class="text-lg font-medium text-gray-400 mb-2">No hay análisis trimestrales registrados</p>
                    <p class="text-sm">Los análisis trimestrales aparecerán aquí una vez que se registren.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para editar análisis trimestral -->
<div id="modalEditarAnalisisTrimestral" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 xl:w-2/3 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Editar Análisis Trimestral</h3>
                <button onclick="cerrarModalEditarTrimestral()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="formEditarAnalisisTrimestral" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Protocolo y Fecha -->
                    <div class="col-span-full grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <label for="edit_tri_protocolo" class="block text-sm font-medium text-gray-700 mb-1">Protocolo</label>
                            <input type="text" id="edit_tri_protocolo" name="protocolo" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div>
                            <label for="edit_tri_fechaanalisis" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Análisis</label>
                            <input type="date" id="edit_tri_fechaanalisis" name="fechaanalisis" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                    </div>

                    <!-- Análisis Nutricional -->
                    <div class="col-span-full">
                        <h4 class="text-md font-semibold text-gray-800 mb-3 p-2 bg-green-50 rounded">
                            <i class="fas fa-apple-alt mr-2 text-green-500"></i>Análisis Nutricional
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="edit_tri_linfocitos" class="block text-sm font-medium text-gray-700 mb-1">Linfocitos</label>
                                <input type="number" step="0.01" id="edit_tri_linfocitos" name="linfocitos" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            </div>
                            <div>
                                <label for="edit_tri_colesterol" class="block text-sm font-medium text-gray-700 mb-1">Colesterol</label>
                                <input type="number" step="0.01" id="edit_tri_colesterol" name="colesterol" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            </div>
                            <div>
                                <label for="edit_tri_albumina" class="block text-sm font-medium text-gray-700 mb-1">Albumina</label>
                                <input type="number" step="0.01" id="edit_tri_albumina" name="albumina" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                    <button type="button" onclick="cerrarModalEditarTrimestral()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                        Actualizar Análisis
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editarAnalisisTrimestral(id) {
    // Hacer petición AJAX para obtener los datos del análisis
    fetch(`/analisis-trimestrales/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            // Llenar el formulario con los datos
            document.getElementById('edit_tri_protocolo').value = data.protocolo || '';
            document.getElementById('edit_tri_fechaanalisis').value = data.fechaanalisis || '';
            document.getElementById('edit_tri_linfocitos').value = data.linfocitos || '';
            document.getElementById('edit_tri_colesterol').value = data.colesterol || '';
            document.getElementById('edit_tri_albumina').value = data.albumina || '';
            
            // Establecer la acción del formulario
            document.getElementById('formEditarAnalisisTrimestral').action = `/analisis-trimestrales/${id}`;
            
            // Mostrar el modal
            document.getElementById('modalEditarAnalisisTrimestral').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarError('Error', 'Error al cargar los datos del análisis trimestral');
        });
}

function cerrarModalEditarTrimestral() {
    document.getElementById('modalEditarAnalisisTrimestral').classList.add('hidden');
}

// Cerrar modal al hacer clic fuera de él
document.getElementById('modalEditarAnalisisTrimestral').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModalEditarTrimestral();
    }
});
</script>
