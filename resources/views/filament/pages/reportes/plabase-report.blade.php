<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center space-x-4 mb-4">
                <div class="p-3 bg-blue-100 rounded-full">
                    <x-heroicon-o-document-chart-bar class="w-8 h-8 text-blue-600" />
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Reporte PLABASE</h2>
                    <p class="text-gray-600">Genere reportes PLABASE para todos los pacientes</p>
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-medium text-gray-900 mb-2">¿Qué es PLABASE?</h3>
                <p class="text-gray-700 mb-4">
                    PLABASE es un sistema de registro y reporte para centros de hemodiálisis que permite 
                    generar informes detallados sobre los tratamientos y análisis de los pacientes.
                </p>
                
                <div class="flex items-center justify-center py-8">
                    <div class="text-center">
                        <x-heroicon-o-document-text class="w-16 h-16 text-gray-400 mx-auto mb-4" />
                        <p class="text-gray-500">
                            Haga clic en "Generar Reporte PLABASE" en la parte superior 
                            para seleccionar el mes y generar el reporte.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
