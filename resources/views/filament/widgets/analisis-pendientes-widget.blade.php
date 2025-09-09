<x-filament-widgets::widget>
    @if(auth()->user() && auth()->user()->email !== 'liliana.monje@chcdg.com')
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-x-3">
                        <x-heroicon-o-clock class="h-6 w-6" />
                        Análisis Diarios Pendientes
                    </div>
                    @if(count($analisisPendientes) > 0)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                            {{ count($analisisPendientes) }} pendiente{{ count($analisisPendientes) !== 1 ? 's' : '' }}
                        </span>
                    @endif
                </div>
            </x-slot>

            <div class="space-y-4">
                @if(count($analisisPendientes) > 0)
                    <!-- Lista de análisis pendientes como acordeones -->
                    <div class="space-y-2">
                        @foreach($analisisPendientes as $index => $analisis)
                            <div x-data="{ open: false }" class="border border-gray-200 rounded-lg dark:border-gray-700 bg-amber-50 dark:bg-amber-900/20 overflow-hidden">
                                <!-- Header del acordeón (siempre visible) -->
                                <div class="p-4 cursor-pointer" @click="open = !open">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center gap-4">
                                            <!-- Indicador de expandir/contraer -->
                                            <x-heroicon-o-chevron-right 
                                                class="h-5 w-5 text-gray-400 transition-transform duration-200"
                                                x-bind:class="{ 'rotate-90': open }"
                                            />
                                            
                                            <!-- Nombre del paciente -->
                                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $analisis['paciente']['apellido'] ?? 'N/A' }}, {{ $analisis['paciente']['nombre'] ?? 'N/A' }}
                                            </h4>
                                            
                                            <!-- Fecha -->
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $analisis['fechaanalisis'] ? \Carbon\Carbon::parse($analisis['fechaanalisis'])->format('d/m/Y') : 'N/A' }}
                                            </span>
                                            
                                            <!-- Badge de estado -->
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                                                Pendiente
                                            </span>
                                        </div>
                                        
                                        <!-- Botón completar (siempre visible) -->
                                        <button 
                                            wire:click.stop="abrirModal({{ $analisis['id'] }})"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors"
                                        >
                                            <x-heroicon-s-pencil class="h-4 w-4 mr-1" />
                                            Completar
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Contenido expandible del acordeón -->
                                <div 
                                    x-show="open" 
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                                    x-transition:enter-end="opacity-100 transform translate-y-0"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 transform translate-y-0"
                                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                                    class="px-4 pb-4"
                                    style="display: none;"
                                >
                                    <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                            <div class="bg-white dark:bg-gray-800 p-3 rounded-md border border-gray-200 dark:border-gray-600">
                                                <span class="font-small text-gray-700 dark:text-gray-300">Peso Pre:</span>
                                                <p class="text-md font-semibold text-gray-900 dark:text-gray-100">{{ $analisis['pesopre'] ?? 'N/A' }} kg</p>
                                            </div>
                                            <div class="bg-white dark:bg-gray-800 p-3 rounded-md border border-gray-200 dark:border-gray-600">
                                                <span class="font-small text-gray-700 dark:text-gray-300">Tensión Arterial Pre:</span>
                                                <p class="text-md font-semibold text-gray-900 dark:text-gray-100">
                                                    {{ $analisis['taspre'] ?? 'N/A' }}/{{ $analisis['tadpre'] ?? 'N/A' }}
                                                </p>
                                            </div>
                                            <div class="bg-white dark:bg-gray-800 p-3 rounded-md border border-gray-200 dark:border-gray-600">
                                                <span class="font-small text-gray-700 dark:text-gray-300">Tipo de Filtro:</span>
                                                <p class="text-md font-semibold text-gray-900 dark:text-gray-100">
                                                    {{ $analisis['tipo_filtro']['nombre'] ?? 'N/A' }}
                                                </p>
                                            </div>
                                            <div class="bg-white dark:bg-gray-800 p-3 rounded-md border border-gray-200 dark:border-gray-600">
                                                <span class="font-small text-gray-700 dark:text-gray-300">Interdiálisis:</span>
                                                <p class="text-md font-semibold text-gray-900 dark:text-gray-100">{{ $analisis['interdialitico'] ?? 'N/A' }}</p>
                                            </div>
                                            <div class="bg-white dark:bg-gray-800 p-3 rounded-md border border-gray-200 dark:border-gray-600">
                                                <span class="font-small text-gray-700 dark:text-gray-300">% Rel. Peso Seco/Pre:</span>
                                                <p class="text-md font-semibold text-gray-900 dark:text-gray-100">{{ $analisis['relpesosecopesopre'] ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No hay análisis pendientes</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Todos los análisis diarios están completos.</p>
                    </div>
                @endif
            </div>
        </x-filament::section>

        <!-- Modal simplificado para completar análisis -->
        @if($mostrarModal && $analisisSeleccionado)
            <div x-data="{ show: @entangle('mostrarModal') }" 
                 x-show="show" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 overflow-y-auto"
                 style="display: none;"
                 @keydown.escape="$wire.cerrarModal()">
                
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <!-- Overlay -->
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="$wire.cerrarModal()"></div>

                    <!-- Modal -->
                    <div class="inline-block align-middle bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <!-- Header del modal -->
                        <div class="bg-green-500 px-6 py-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg font-semibold text-black">
                                        Análisis Diario Post
                                    </h3>
                                    <p class="text-green-100 text-sm">
                                        {{ $analisisSeleccionado['paciente']['apellido'] ?? '' }}, {{ $analisisSeleccionado['paciente']['nombre'] ?? '' }}
                                        - {{ $analisisSeleccionado['fechaanalisis'] ? \Carbon\Carbon::parse($analisisSeleccionado['fechaanalisis'])->format('d/m/Y') : 'N/A' }}
                                    </p>
                                </div>
                               
                            </div>
                        </div>

                        <form wire:submit.prevent="completarAnalisis">
                            <div class="px-6 py-6">
                                <!-- Formulario para datos Post-Diálisis -->
                                <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                                    {{ $this->form }}
                                </div>
                            </div>
                            
                            <!-- Footer del modal -->
                            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3">
                                <button type="submit" 
                                        class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                    <x-heroicon-s-check class="h-4 w-4 mr-2" />
                                    Completar Análisis
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endif
</x-filament-widgets::widget>
