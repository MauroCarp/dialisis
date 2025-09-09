<!-- Análisis Mensuales -->
<div x-show="activeTab === 'mensuales'" x-transition class="space-y-4">
    <!-- Formulario para nuevo Análisis Mensual -->
    <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-6">
        <h4 class="text-md font-semibold text-gray-800 mb-4">
            <i class="fas fa-calendar-alt mr-2 text-teal-600"></i>
            Nuevo Análisis Mensual
        </h4>
        <form method="POST" action="{{ route('analisis-mensuales.store', $paciente->id) }}">
            @csrf
            <div class="mb-2 grid grid-cols-1 md:grid-cols-1 gap-1">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Fecha Análisis</label>
                    <input type="date" name="fechaanalisis" value="{{ now()->format('Y-m-d') }}" class="w-full border rounded px-3 py-2" required>

                </div>
            </div>
            <div class="mb-4 grid grid-cols-6 md:grid-cols-6 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Protocolo</label>
                    <input type="text" name="protocolo" class="w-full border rounded px-3 py-2">
                </div>
                <!-- Hematología -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Hemoglobina</label>
                    <input type="number" step="0.01" name="hemoglobina" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Hematocrito</label>
                    <input type="number" step="0.01" name="hematocrito" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Rto. Blancos</label>
                    <input type="number" step="0.01" name="rto_blancos" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Rto. Rojos</label>
                    <input type="number" step="0.01" name="rto_rojos" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Rto. Plaquetas</label>
                    <input type="number" step="0.01" name="rto_plaquetas" class="w-full border rounded px-3 py-2">
                </div>
                <!-- Función Renal -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Creatinina</label>
                    <input type="number" step="0.01" name="creatinina" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Uremia Pre</label>
                    <input type="number" step="0.01" name="uremia_pre" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Uremia Post</label>
                    <input type="number" step="0.01" name="uremia_post" class="w-full border rounded px-3 py-2">
                </div>
                <!-- Electrolitos -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Sodio</label>
                    <input type="number" step="0.01" name="sodio" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Potasio</label>
                    <input type="number" step="0.01" name="potasio" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Calcemia</label>
                    <input type="number" step="0.01" name="calcemia" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Fosfatemia</label>
                    <input type="number" step="0.01" name="fosfatemia" class="w-full border rounded px-3 py-2">
                </div>
                <!-- Función Hepática -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">GPT</label>
                    <input type="number" step="0.01" name="gpt" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">GOT</label>
                    <input type="number" step="0.01" name="got" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Fosfatasa Alcalina</label>
                    <input type="number" step="0.01" name="fosfatasa_alcalina" class="w-full border rounded px-3 py-2">
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

    <!-- Lista de Análisis Mensuales -->
    <div x-data="{ open: false }" class="space-y-2">
        <button 
            @click="open = !open"
            class="w-full flex items-center justify-between px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded font-semibold text-gray-700 focus:outline-none transition-colors duration-200"
            type="button">
            <span>
                <span x-show="!open">Mostrar</span>
                <span x-show="open">Ocultar</span>
                Análisis Mensuales ({{ isset($analisisData['mensuales']) ? $analisisData['mensuales']->count() : 0 }})
            </span>
            <svg x-show="!open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
            <svg x-show="open" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"></path></svg>
        </button>
        <div x-show="open" class="space-y-4" x-transition>
            @if(isset($analisisData['mensuales']) && $analisisData['mensuales']->count() > 0)
                @foreach($analisisData['mensuales'] as $analisis)
                    <div class="border border-gray-200 rounded-lg p-4 bg-white">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex space-x-2">
                                @if($analisis->fechaanalisis)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
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
                                    onclick="editarAnalisisMensual({{ $analisis->id }})"
                                    class="inline-flex items-center px-2 py-1 rounded text-xs bg-blue-500 hover:bg-blue-600 text-white transition-colors duration-200"
                                    title="Editar Análisis Mensual">
                                    <i class="fas fa-edit mr-1"></i>
                                    Editar
                                </button>
                                
                                <!-- Botón de eliminar -->
                                <form method="POST" action="{{ route('analisis-mensuales.destroy', $analisis->id) }}" 
                                      class="inline-block"
                                      onsubmit="return confirmarEliminacionForm(event, '¿Está seguro que desea eliminar este análisis mensual? Esta acción no se puede deshacer.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-2 py-1 rounded text-xs bg-red-500 hover:bg-red-600 text-white transition-colors duration-200"
                                            title="Eliminar Análisis Mensual">
                                        <i class="fas fa-trash mr-1"></i>
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Hematología -->
                        <div class="mb-4">
                            <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">
                                <i class="fas fa-tint mr-1 text-red-500"></i>
                                Hematología
                            </h5>
                            <div class="grid grid-cols-3 md:grid-cols-5 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Hemoglobina:</span>
                                    <p class="font-medium">{{ $analisis->hemoglobina ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Hematocrito:</span>
                                    <p class="font-medium">{{ $analisis->hematocrito ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Rto. Blancos:</span>
                                    <p class="font-medium">{{ $analisis->rto_blancos ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Rto. Rojos:</span>
                                    <p class="font-medium">{{ $analisis->rto_rojos ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Rto. Plaquetas:</span>
                                    <p class="font-medium">{{ $analisis->rto_plaquetas ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Función Renal -->
                        <div class="mb-4">
                            <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">
                                <i class="fas fa-kidneys mr-1 text-orange-500"></i>
                                Función Renal
                            </h5>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Creatinina:</span>
                                    <p class="font-medium">{{ $analisis->creatinina ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Uremia Pre:</span>
                                    <p class="font-medium">{{ $analisis->uremia_pre ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Uremia Post:</span>
                                    <p class="font-medium">{{ $analisis->uremia_post ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Urea/Creatinina:</span>
                                    <p class="font-medium">{{ $analisis->urea_creatinina ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Adecuación de Diálisis -->
                        <div class="mb-4">
                            <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">
                                <i class="fas fa-chart-line mr-1 text-green-500"></i>
                                Adecuación de Diálisis
                            </h5>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">RPU:</span>
                                    <p class="font-medium">{{ $analisis->rpu ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">KT/V Daugiras:</span>
                                    <p class="font-medium">{{ $analisis->ktv_daugiras ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">KT/V Basile:</span>
                                    <p class="font-medium">{{ $analisis->ktv_basile ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">TAC Urea:</span>
                                    <p class="font-medium">{{ $analisis->tac_urea ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Electrolitos -->
                        <div class="mb-4">
                            <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">
                                <i class="fas fa-atom mr-1 text-purple-500"></i>
                                Electrolitos
                            </h5>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Sodio:</span>
                                    <p class="font-medium">{{ $analisis->sodio ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Potasio:</span>
                                    <p class="font-medium">{{ $analisis->potasio ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Calcemia:</span>
                                    <p class="font-medium">{{ $analisis->calcemia ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Fosfatemia:</span>
                                    <p class="font-medium">{{ $analisis->fosfatemia ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Función Hepática e Inflamación -->
                        <div class="mb-2">
                            <h5 class="text-sm font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">
                                <i class="fas fa-liver mr-1 text-yellow-500"></i>
                                Función Hepática e Inflamación
                            </h5>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">GPT:</span>
                                    <p class="font-medium">{{ $analisis->gpt ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">GOT:</span>
                                    <p class="font-medium">{{ $analisis->got ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Fosfatasa Alcalina:</span>
                                    <p class="font-medium">{{ $analisis->fosfatasa_alcalina ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">PCR:</span>
                                    <p class="font-medium">{{ $analisis->pcr ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-flask text-gray-300 text-4xl mb-4"></i>
                    <p class="text-lg font-medium text-gray-400 mb-2">No hay análisis mensuales registrados</p>
                    <p class="text-sm">Los análisis mensuales aparecerán aquí una vez que se registren.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para editar análisis mensual -->
<div id="modalEditarAnalisisMensual" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 xl:w-2/3 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Editar Análisis Mensual</h3>
                <button onclick="cerrarModalEditar()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="formEditarAnalisisMensual" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Protocolo y Fecha -->
                    <div class="col-span-full grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <label for="edit_protocolo" class="block text-sm font-medium text-gray-700 mb-1">Protocolo</label>
                            <input type="text" id="edit_protocolo" name="protocolo" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="edit_fechaanalisis" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Análisis</label>
                            <input type="date" id="edit_fechaanalisis" name="fechaanalisis" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Hematología -->
                    <div class="col-span-full">
                        <h4 class="text-md font-semibold text-gray-800 mb-3 p-2 bg-red-50 rounded">
                            <i class="fas fa-tint mr-2 text-red-500"></i>Hematología
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label for="edit_hemoglobina" class="block text-sm font-medium text-gray-700 mb-1">Hemoglobina</label>
                                <input type="number" step="0.01" id="edit_hemoglobina" name="hemoglobina" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="edit_hematocrito" class="block text-sm font-medium text-gray-700 mb-1">Hematocrito</label>
                                <input type="number" step="0.01" id="edit_hematocrito" name="hematocrito" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="edit_rto_blancos" class="block text-sm font-medium text-gray-700 mb-1">Rto. Blancos</label>
                                <input type="number" step="0.01" id="edit_rto_blancos" name="rto_blancos" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="edit_rto_rojos" class="block text-sm font-medium text-gray-700 mb-1">Rto. Rojos</label>
                                <input type="number" step="0.01" id="edit_rto_rojos" name="rto_rojos" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="edit_rto_plaquetas" class="block text-sm font-medium text-gray-700 mb-1">Rto. Plaquetas</label>
                                <input type="number" step="0.01" id="edit_rto_plaquetas" name="rto_plaquetas" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Función Renal -->
                    <div class="col-span-full">
                        <h4 class="text-md font-semibold text-gray-800 mb-3 p-2 bg-blue-50 rounded">
                            <i class="fas fa-kidneys mr-2 text-blue-500"></i>Función Renal
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label for="edit_creatinina" class="block text-sm font-medium text-gray-700 mb-1">Creatinina</label>
                                <input type="number" step="0.01" id="edit_creatinina" name="creatinina" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="edit_uremia_pre" class="block text-sm font-medium text-gray-700 mb-1">Uremia Pre</label>
                                <input type="number" step="0.01" id="edit_uremia_pre" name="uremia_pre" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="edit_uremia_post" class="block text-sm font-medium text-gray-700 mb-1">Uremia Post</label>
                                <input type="number" step="0.01" id="edit_uremia_post" name="uremia_post" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Electrolitos -->
                    <div class="col-span-full">
                        <h4 class="text-md font-semibold text-gray-800 mb-3 p-2 bg-green-50 rounded">
                            <i class="fas fa-atom mr-2 text-green-500"></i>Electrolitos
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label for="edit_sodio" class="block text-sm font-medium text-gray-700 mb-1">Sodio</label>
                                <input type="number" step="0.01" id="edit_sodio" name="sodio" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="edit_potasio" class="block text-sm font-medium text-gray-700 mb-1">Potasio</label>
                                <input type="number" step="0.01" id="edit_potasio" name="potasio" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="edit_calcemia" class="block text-sm font-medium text-gray-700 mb-1">Calcemia</label>
                                <input type="number" step="0.01" id="edit_calcemia" name="calcemia" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="edit_fosfatemia" class="block text-sm font-medium text-gray-700 mb-1">Fosfatemia</label>
                                <input type="number" step="0.01" id="edit_fosfatemia" name="fosfatemia" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Función Hepática -->
                    <div class="col-span-full">
                        <h4 class="text-md font-semibold text-gray-800 mb-3 p-2 bg-orange-50 rounded">
                            <i class="fas fa-liver mr-2 text-orange-500"></i>Función Hepática
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label for="edit_gpt" class="block text-sm font-medium text-gray-700 mb-1">GPT</label>
                                <input type="number" step="0.01" id="edit_gpt" name="gpt" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="edit_got" class="block text-sm font-medium text-gray-700 mb-1">GOT</label>
                                <input type="number" step="0.01" id="edit_got" name="got" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="edit_fosfatasa_alcalina" class="block text-sm font-medium text-gray-700 mb-1">Fosfatasa Alcalina</label>
                                <input type="number" step="0.01" id="edit_fosfatasa_alcalina" name="fosfatasa_alcalina" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                    <button type="button" onclick="cerrarModalEditar()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Actualizar Análisis
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editarAnalisisMensual(id) {
    // Hacer petición AJAX para obtener los datos del análisis
    fetch(`/analisis-mensuales/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            // Llenar el formulario con los datos
            console.log(data)
            document.getElementById('edit_protocolo').value = data.protocolo || '';

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
            
            document.getElementById('edit_hemoglobina').value = data.hemoglobina || '';
            document.getElementById('edit_hematocrito').value = data.hematocrito || '';
            document.getElementById('edit_rto_blancos').value = data.rto_blancos || '';
            document.getElementById('edit_rto_rojos').value = data.rto_rojos || '';
            document.getElementById('edit_rto_plaquetas').value = data.rto_plaquetas || '';
            document.getElementById('edit_creatinina').value = data.creatinina || '';
            document.getElementById('edit_uremia_pre').value = data.uremia_pre || '';
            document.getElementById('edit_uremia_post').value = data.uremia_post || '';
            document.getElementById('edit_sodio').value = data.sodio || '';
            document.getElementById('edit_potasio').value = data.potasio || '';
            document.getElementById('edit_calcemia').value = data.calcemia || '';
            document.getElementById('edit_fosfatemia').value = data.fosfatemia || '';
            document.getElementById('edit_gpt').value = data.gpt || '';
            document.getElementById('edit_got').value = data.got || '';
            document.getElementById('edit_fosfatasa_alcalina').value = data.fosfatasa_alcalina || '';
            
            // Establecer la acción del formulario
            document.getElementById('formEditarAnalisisMensual').action = `/analisis-mensuales/${id}`;
            
            // Mostrar el modal
            document.getElementById('modalEditarAnalisisMensual').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarError('Error', 'Error al cargar los datos del análisis mensual');
        });
}

function cerrarModalEditar() {
    document.getElementById('modalEditarAnalisisMensual').classList.add('hidden');
}

// Cerrar modal al hacer clic fuera de él
document.getElementById('modalEditarAnalisisMensual').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModalEditar();
    }
});
</script>
