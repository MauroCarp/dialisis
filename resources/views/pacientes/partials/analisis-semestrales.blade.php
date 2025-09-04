<!--        <h4 class="text-md font-semibold text-gray-800 mb-4">
            <i class="fas fa-calendar-alt mr-2 text-indigo-600"></i>
            Nuevo Análisis Semestral
        </h4>
        
        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <strong>¡Errores de validación!</strong>
                <ul class="mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('analisis-semestrales.store', $paciente->id) }}">isis Semestrales -->
<div x-show="activeTab === 'semestrales'" x-transition class="space-y-4">
    <!-- Formulario para nuevo Análisis Semestral -->
    <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-6">
        <h4 class="text-md font-semibold text-gray-800 mb-4">
            <i class="fas fa-calendar-alt mr-2 text-indigo-600"></i>
            Nuevo Análisis Semestral
        </h4>
        <form method="POST" action="{{ route('analisis-semestrales.store', $paciente->id) }}">
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
            <div class="mb-4 grid grid-cols-2 md:grid-cols-3 gap-4">
                
                <!-- Marcadores Virológicos -->
                <div class="col-span-2 md:col-span-3">
                    <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">
                        <i class="fas fa-virus mr-1 text-red-500"></i>
                        Marcadores Virológicos
                    </h5>
                </div>
                
                <div class="flex items-center">
                    <input type="hidden" name="hbsag" value="0">
                    <input type="checkbox" id="hbsag" name="hbsag" value="1" class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="hbsag" class="text-sm font-medium text-gray-700">HBsAg</label>
                </div>
                
                <div class="flex items-center">
                    <input type="hidden" name="antihbsag" value="0">
                    <input type="checkbox" id="antihbsag" name="antihbsag" value="1" class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="antihbsag" class="text-sm font-medium text-gray-700">Anti-HBsAg</label>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Valor Anti-HBsAg</label>
                    <input type="number" step="0.01" name="valorantihbsag" class="w-full border rounded px-3 py-2">
                </div>
                
                <div class="flex items-center">
                    <input type="hidden" name="antihcv" value="0">
                    <input type="checkbox" id="antihcv" name="antihcv" value="1" class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="antihcv" class="text-sm font-medium text-gray-700">Anti-HCV</label>
                </div>
                
                <div class="flex items-center">
                    <input type="hidden" name="antihiv" value="0">
                    <input type="checkbox" id="antihiv" name="antihiv" value="1" class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="antihiv" class="text-sm font-medium text-gray-700">Anti-HIV</label>
                </div>
                
                <div class="flex items-center">
                    <input type="hidden" name="anticore" value="0">
                    <input type="checkbox" id="anticore" name="anticore" value="1" class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="anticore" class="text-sm font-medium text-gray-700">Anti-Core</label>
                </div>
                
                <!-- Seguimiento Metabólico -->
                <div class="col-span-2 md:col-span-3">
                    <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">
                        <i class="fas fa-chart-line mr-1 text-blue-500"></i>
                        Seguimiento Metabólico
                    </h5>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">PTH</label>
                    <input type="number" step="0.01" name="pth" class="w-full border rounded px-3 py-2">
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Ferritina</label>
                    <input type="number" step="0.01" name="ferritina" class="w-full border rounded px-3 py-2">
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Ferremia</label>
                    <input type="number" step="0.01" name="ferremia" class="w-full border rounded px-3 py-2">
                </div>
            </div>
            <div class="flex justify-end">
                <button 
                    type="submit"
                    class="px-4 py-2 rounded bg-indigo-600 hover:bg-indigo-700 text-white font-bold transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Guardar
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Análisis Semestrales -->
    <div x-data="{ open: false }" class="space-y-2">
        <button 
            @click="open = !open"
            class="w-full flex items-center justify-between px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded font-semibold text-gray-700 focus:outline-none transition-colors duration-200"
            type="button">
            <span>
                <span x-show="!open">Mostrar</span>
                <span x-show="open">Ocultar</span>
                Análisis Semestrales ({{ isset($analisisData['semestrales']) ? $analisisData['semestrales']->count() : 0 }})
            </span>
            <svg x-show="!open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
            <svg x-show="open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"></path></svg>
        </button>
        <div x-show="open" class="space-y-4" x-transition>
            @if(isset($analisisData['semestrales']) && $analisisData['semestrales']->count() > 0)
                @foreach($analisisData['semestrales'] as $analisis)
                    <div class="border border-gray-200 rounded-lg p-4 bg-white">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex space-x-2">
                                @if($analisis->fechaanalisis)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
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
                                    onclick="editarAnalisisSemestral({{ $analisis->id }})"
                                    class="inline-flex items-center px-2 py-1 rounded text-xs bg-blue-500 hover:bg-blue-600 text-white transition-colors duration-200"
                                    title="Editar Análisis Semestral">
                                    <i class="fas fa-edit mr-1"></i>
                                    Editar
                                </button>
                                
                                <!-- Botón de eliminar -->
                                <form method="POST" action="{{ route('analisis-semestrales.destroy', $analisis->id) }}" 
                                      class="inline-block"
                                      onsubmit="return confirm('¿Está seguro que desea eliminar este análisis semestral? Esta acción no se puede deshacer.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-2 py-1 rounded text-xs bg-red-500 hover:bg-red-600 text-white transition-colors duration-200"
                                            title="Eliminar Análisis Semestral">
                                        <i class="fas fa-trash mr-1"></i>
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="mb-2">
                            <!-- Marcadores Virológicos -->
                            <div class="mb-4">
                                <h5 class="text-sm font-semibold text-gray-700 mb-3 border-b border-gray-200 pb-1">
                                    <i class="fas fa-virus mr-1 text-red-500"></i>
                                    Marcadores Virológicos
                                </h5>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500">HBsAg:</span>
                                        <p class="font-medium">
                                            @if($analisis->hbsag)
                                                <span class="text-red-600">
                                                    <i class="fas fa-check-circle mr-1"></i>Positivo
                                                </span>
                                            @else
                                                <span class="text-green-600">
                                                    <i class="fas fa-times-circle mr-1"></i>Negativo
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Anti-HBsAg:</span>
                                        <p class="font-medium">
                                            @if($analisis->antihbsag)
                                                <span class="text-green-600">
                                                    <i class="fas fa-check-circle mr-1"></i>Positivo
                                                </span>
                                            @else
                                                <span class="text-gray-600">
                                                    <i class="fas fa-times-circle mr-1"></i>Negativo
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                    @if($analisis->valorantihbsag)
                                        <div>
                                            <span class="text-gray-500">Valor Anti-HBsAg:</span>
                                            <p class="font-medium">{{ $analisis->valorantihbsag }}</p>
                                        </div>
                                    @endif
                                    <div>
                                        <span class="text-gray-500">Anti-HCV:</span>
                                        <p class="font-medium">
                                            @if($analisis->antihcv)
                                                <span class="text-red-600">
                                                    <i class="fas fa-check-circle mr-1"></i>Positivo
                                                </span>
                                            @else
                                                <span class="text-green-600">
                                                    <i class="fas fa-times-circle mr-1"></i>Negativo
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Anti-HIV:</span>
                                        <p class="font-medium">
                                            @if($analisis->antihiv)
                                                <span class="text-red-600">
                                                    <i class="fas fa-check-circle mr-1"></i>Positivo
                                                </span>
                                            @else
                                                <span class="text-green-600">
                                                    <i class="fas fa-times-circle mr-1"></i>Negativo
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Anti-Core:</span>
                                        <p class="font-medium">
                                            @if($analisis->anticore)
                                                <span class="text-red-600">
                                                    <i class="fas fa-check-circle mr-1"></i>Positivo
                                                </span>
                                            @else
                                                <span class="text-green-600">
                                                    <i class="fas fa-times-circle mr-1"></i>Negativo
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Seguimiento Metabólico -->
                            <div>
                                <h5 class="text-sm font-semibold text-gray-700 mb-3 border-b border-gray-200 pb-1">
                                    <i class="fas fa-chart-line mr-1 text-blue-500"></i>
                                    Seguimiento Metabólico
                                </h5>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500">PTH:</span>
                                        <p class="font-medium">{{ $analisis->pth ?? '0' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Ferritina:</span>
                                        <p class="font-medium">{{ $analisis->ferritina ?? '0' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Ferremia:</span>
                                        <p class="font-medium">{{ $analisis->ferremia ?? '0' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-vials text-gray-300 text-4xl mb-4"></i>
                    <p class="text-lg font-medium text-gray-400 mb-2">No hay análisis semestrales registrados</p>
                    <p class="text-sm">Los análisis semestrales aparecerán aquí una vez que se registren.</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Modal de Edición -->
    <div id="modalEditarSemestral" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-4xl w-full max-h-screen overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Editar Análisis Semestral</h3>
                        <button onclick="cerrarModalSemestral()" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <form id="formEditarSemestral" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                            <div class="col-span-2 md:col-span-3">
                                <label class="block text-gray-700 text-sm font-semibold mb-2">Fecha de Análisis</label>
                                <input type="date" id="edit_fechaanalisis" name="fechaanalisis" class="w-full border rounded px-3 py-2" style="background-color: white !important; color: black !important;">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2">Protocolo</label>
                                <input type="text" id="edit_protocolo" name="protocolo" class="w-full border rounded px-3 py-2" style="background-color: white !important; color: black !important;">
                            </div>
                            
                            <!-- Marcadores Virológicos -->
                            <div class="col-span-2 md:col-span-3">
                                <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">
                                    <i class="fas fa-virus mr-1 text-red-500"></i>
                                    Marcadores Virológicos
                                </h5>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="hidden" name="hbsag" value="0">
                                <input type="checkbox" id="edit_hbsag" name="hbsag" value="1" class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="edit_hbsag" class="text-sm font-medium text-gray-700">HBsAg</label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="hidden" name="antihbsag" value="0">
                                <input type="checkbox" id="edit_antihbsag" name="antihbsag" value="1" class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="edit_antihbsag" class="text-sm font-medium text-gray-700">Anti-HBsAg</label>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2">Valor Anti-HBsAg</label>
                                <input type="number" step="0.01" id="edit_valorantihbsag" name="valorantihbsag" class="w-full border rounded px-3 py-2">
                            </div>
                            
                            <div class="flex items-center">
                                <input type="hidden" name="antihcv" value="0">
                                <input type="checkbox" id="edit_antihcv" name="antihcv" value="1" class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="edit_antihcv" class="text-sm font-medium text-gray-700">Anti-HCV</label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="hidden" name="antihiv" value="0">
                                <input type="checkbox" id="edit_antihiv" name="antihiv" value="1" class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="edit_antihiv" class="text-sm font-medium text-gray-700">Anti-HIV</label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="hidden" name="anticore" value="0">
                                <input type="checkbox" id="edit_anticore" name="anticore" value="1" class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="edit_anticore" class="text-sm font-medium text-gray-700">Anti-Core</label>
                            </div>
                            
                            <!-- Seguimiento Metabólico -->
                            <div class="col-span-2 md:col-span-3">
                                <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">
                                    <i class="fas fa-chart-line mr-1 text-blue-500"></i>
                                    Seguimiento Metabólico
                                </h5>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2">PTH</label>
                                <input type="number" step="0.01" id="edit_pth" name="pth" class="w-full border rounded px-3 py-2">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2">Ferritina</label>
                                <input type="number" step="0.01" id="edit_ferritina" name="ferritina" class="w-full border rounded px-3 py-2" style="background-color: white !important; color: black !important;">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-semibold mb-2">Ferremia</label>
                                <input type="number" step="0.01" id="edit_ferremia" name="ferremia" class="w-full border rounded px-3 py-2">
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-2">
                            <button type="button" onclick="cerrarModalSemestral()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded">
                                Cancelar
                            </button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded">
                                <i class="fas fa-save mr-2"></i>
                                Actualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editarAnalisisSemestral(id) {
    console.log('Editando análisis semestral ID:', id);
    
    // Obtener datos del análisis via AJAX
    fetch(`/analisis-semestrales/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            console.log('Datos recibidos completos:', data); // Debug
            
            // Verificar que el modal esté visible primero
            document.getElementById('modalEditarSemestral').classList.remove('hidden');
            
            // Dar un momento para que el modal se renderice
            setTimeout(() => {
                // Llenar el formulario con los datos usando múltiples métodos
                const protocoloInput = document.getElementById('edit_protocolo');
                const fechaInput = document.getElementById('edit_fechaanalisis');
                const ferritinaInput = document.getElementById('edit_ferritina');
                
                console.log('Elementos encontrados:', {
                    protocolo: !!protocoloInput,
                    fecha: !!fechaInput,
                    ferritina: !!ferritinaInput
                });
                
                // Usar la función auxiliar para forzar valores
                forzarValorInput('edit_protocolo', data.protocolo || '');
                
                // Fecha con formato especial
                if (data.fechaanalisis) {
                    try {
                        const fecha = new Date(data.fechaanalisis);
                        if (!isNaN(fecha.getTime())) {
                            forzarValorInput('edit_fechaanalisis', fecha.toISOString().split('T')[0]);
                        } else {
                            forzarValorInput('edit_fechaanalisis', new Date().toISOString().split('T')[0]);
                        }
                    } catch (e) {
                        console.error('Error parseando fecha:', e);
                        forzarValorInput('edit_fechaanalisis', new Date().toISOString().split('T')[0]);
                    }
                } else {
                    forzarValorInput('edit_fechaanalisis', new Date().toISOString().split('T')[0]);
                }
                
                // Campos numéricos
                forzarValorInput('edit_valorantihbsag', data.valorantihbsag || '');
                forzarValorInput('edit_pth', data.pth || '');
                forzarValorInput('edit_ferritina', data.ferritina || '');
                forzarValorInput('edit_ferremia', data.ferremia || '');
                
                // Checkboxes
                document.getElementById('edit_hbsag').checked = data.hbsag == 1;
                document.getElementById('edit_antihbsag').checked = data.antihbsag == 1;
                document.getElementById('edit_antihcv').checked = data.antihcv == 1;
                document.getElementById('edit_antihiv').checked = data.antihiv == 1;
                document.getElementById('edit_anticore').checked = data.anticore == 1;
                
                // Configurar action del formulario
                document.getElementById('formEditarSemestral').action = `/analisis-semestrales/${id}`;
                
                console.log('Formulario configurado con action:', document.getElementById('formEditarSemestral').action);
                
                // Verificación final
                setTimeout(() => {
                    console.log('Verificación final de valores:');
                    console.log('Protocolo final:', document.getElementById('edit_protocolo').value);
                    console.log('Fecha final:', document.getElementById('edit_fechaanalisis').value);
                    console.log('Ferritina final:', document.getElementById('edit_ferritina').value);
                }, 100);
                
            }, 200);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los datos del análisis');
        });
}

function cerrarModalSemestral() {
    document.getElementById('modalEditarSemestral').classList.add('hidden');
}

// Función auxiliar para forzar el valor de un input
function forzarValorInput(elementId, valor) {
    const elemento = document.getElementById(elementId);
    if (elemento) {
        // Múltiples métodos para asegurar que el valor se establezca
        elemento.value = valor;
        elemento.setAttribute('value', valor);
        elemento.defaultValue = valor;
        
        // Disparar eventos para notificar el cambio
        const evento = new Event('input', { bubbles: true });
        elemento.dispatchEvent(evento);
        
        const eventoChange = new Event('change', { bubbles: true });
        elemento.dispatchEvent(eventoChange);
        
        console.log(`Valor forzado para ${elementId}:`, elemento.value);
        return true;
    }
    console.error(`No se encontró el elemento ${elementId}`);
    return false;
}

// Agregar debug al envío del formulario
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formEditarSemestral');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Enviando formulario...');
            const formData = new FormData(form);
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }
        });
    }
});
</script>
