<!-- Accesos Vasculares Tab -->
<div class="space-y-6">
    <!-- Formulario para nuevo Acceso Vascular -->
    <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-heart mr-2 text-blue-600"></i>
            Registrar Nuevo Acceso Vascular
        </h4>
        <form method="POST" action="{{ route('accesos-vasculares.store', $paciente->id) }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Fecha de Implante
                    </label>
                    <input type="date" name="fecha_implante" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-list-alt mr-1"></i>
                        Tipo de Acceso
                    </label>
                    <select name="tipo_acceso_vascular_id" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Seleccionar tipo</option>
                        @foreach($tiposAccesoVascular as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        <i class="fas fa-user-md mr-1"></i>
                        Cirujano
                    </label>
                    <div class="flex space-x-2">
                        <select name="cirujano_id" id="cirujano_id" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar cirujano</option>
                            @foreach($cirujanos as $cirujano)
                                <option value="{{ $cirujano->id }}">{{ $cirujano->nombre }}{{ $cirujano->apellido ? ' ' . $cirujano->apellido : '' }}</option>
                            @endforeach
                            <option value="otro">Agregar otro cirujano...</option>
                        </select>
                        <button type="button" id="btnAgregarCirujano" class="px-3 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none" onclick="document.getElementById('modalAgregarCirujano').classList.remove('hidden')">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal para agregar cirujano -->
                <div id="modalAgregarCirujano" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50 hidden">
                    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h5 class="text-lg font-semibold text-gray-800">
                                <i class="fas fa-user-md mr-2"></i>
                                Agregar Cirujano
                            </h5>
                            <button type="button" class="text-gray-400 hover:text-gray-600" onclick="document.getElementById('modalAgregarCirujano').classList.add('hidden')">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <form id="formAgregarCirujano" method="POST" action="{{ route('cirujanos.store') }}">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-semibold mb-2">Nombre del Cirujano</label>
                                <input type="text" name="nombre" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-semibold mb-2">Apellido (Opcional)</label>
                                <input type="text" name="apellido" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-semibold mb-2">Matrícula (Opcional)</label>
                                <input type="text" name="matricula" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="flex justify-end space-x-2">
                                <button type="button" onclick="document.getElementById('modalAgregarCirujano').classList.add('hidden')" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 font-semibold">
                                    <i class="fas fa-times mr-1"></i> Cancelar
                                </button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-semibold">
                                    <i class="fas fa-save mr-1"></i> Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <script>
                    // Si el usuario selecciona "Agregar otro cirujano...", mostrar el modal
                    document.getElementById('cirujano_id').addEventListener('change', function() {
                        if (this.value === 'otro') {
                            document.getElementById('modalAgregarCirujano').classList.remove('hidden');
                            this.value = '';
                        }
                    });

                    // Manejar el envío del formulario de agregar cirujano
                    document.getElementById('formAgregarCirujano').addEventListener('submit', function(e) {
                        e.preventDefault();
                        
                        const formData = new FormData(this);
                        const submitButton = this.querySelector('button[type="submit"]');
                        const originalText = submitButton.innerHTML;
                        
                        // Mostrar estado de carga
                        submitButton.disabled = true;
                        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...';
                        
                        fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Agregar el nuevo cirujano al select
                                const select = document.getElementById('cirujano_id');
                                const option = document.createElement('option');
                                option.value = data.cirujano.id;
                                option.textContent = data.cirujano.nombre + (data.cirujano.apellido ? ' ' + data.cirujano.apellido : '');
                                
                                // Insertar antes de la opción "Agregar otro cirujano..."
                                const lastOption = select.lastElementChild;
                                select.insertBefore(option, lastOption);
                                
                                // Seleccionar el nuevo cirujano
                                select.value = data.cirujano.id;
                                
                                // Cerrar el modal
                                document.getElementById('modalAgregarCirujano').classList.add('hidden');
                                
                                // Limpiar el formulario
                                this.reset();
                                
                                // Mostrar mensaje de éxito
                                alert('Cirujano agregado correctamente');
                            } else {
                                alert('Error al agregar el cirujano');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error al procesar la solicitud');
                        })
                        .finally(() => {
                            // Restaurar el botón
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalText;
                        });
                    });
                </script>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    <i class="fas fa-sticky-note mr-1"></i>
                    Observaciones
                </label>
                <textarea name="observaciones" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Observaciones adicionales sobre el acceso vascular..."></textarea>
            </div>
            
            <div class="flex justify-end">
                <button 
                    type="submit"
                    class="px-6 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white font-bold transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i>
                    Guardar Acceso Vascular
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Accesos Vasculares -->
    <div class="bg-white border border-gray-200 rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-list mr-2 text-gray-600"></i>
                Historial de Accesos Vasculares
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $paciente->accesosVasculares ? $paciente->accesosVasculares->count() : 0 }} registros
                </span>
            </h4>
        </div>
        
        <div class="p-6">
            @if($paciente->accesosVasculares && $paciente->accesosVasculares->count() > 0)
                <div class="space-y-4">
                    @foreach($paciente->accesosVasculares as $acceso)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center space-x-2">
                                    
                                    @if($acceso->tipoAccesoVascular)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-tag mr-1"></i>
                                            {{ $acceso->tipoAccesoVascular->nombre }}
                                        </span>
                                    @endif
                                </div>
                                
                                @if($acceso->fechaacceso)
                                    <span class="text-sm text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ \Carbon\Carbon::parse($acceso->fechaacceso)->format('d/m/Y') }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                
                                @if($acceso->cirujano)
                                    <div>
                                        <span class="text-gray-500 font-medium">
                                            <i class="fas fa-user-md mr-1"></i>
                                            Cirujano:
                                        </span>
                                        <p class="font-medium text-gray-800">{{ $acceso->cirujano->nombre }} {{ $acceso->cirujano->apellido  }}</p>
                                    </div>
                                @endif
                                
                            </div>
                            
                            @if($acceso->observaciones)
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <span class="text-gray-500 font-medium text-sm">
                                        <i class="fas fa-sticky-note mr-1"></i>
                                        Observaciones:
                                    </span>
                                    <p class="text-sm text-gray-700 mt-1">{{ $acceso->observaciones }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-heart text-gray-400 text-3xl"></i>
                    </div>
                    <p class="text-lg font-medium text-gray-400 mb-2">No hay accesos vasculares registrados</p>
                    <p class="text-sm text-gray-500">Los accesos vasculares del paciente aparecerán aquí una vez que se registren.</p>
                </div>
            @endif
        </div>
    </div>
</div>
