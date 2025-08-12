<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Reporte PLABASE por Paciente
                </h3>
                <div class="mt-2 max-w-xl text-sm text-gray-500">
                    <p>
                        Genere un reporte PLABASE detallado para un paciente específico, incluyendo todos los datos clínicos mensuales, trimestrales y semestrales.
                    </p>
                </div>
                <div class="mt-5">
                    <div class="rounded-md bg-blue-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">
                                    Información del reporte
                                </h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>El reporte incluye datos mensuales, trimestrales y semestrales</li>
                                        <li>Los campos calculados se generan automáticamente</li>
                                        <li>El formato es vertical con Campo-Valor para mejor legibilidad</li>
                                        <li>Solo se muestran pacientes activos (sin fecha de egreso)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
