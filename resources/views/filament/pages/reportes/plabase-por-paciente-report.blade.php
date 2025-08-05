<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center space-x-4 mb-4">
                <div class="p-3 bg-green-100 rounded-full">
                    <x-heroicon-o-user-circle class="w-8 h-8 text-green-600" />
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Reporte PLABASE por Paciente</h2>
                    <p class="text-gray-600">Genere reportes PLABASE individuales por paciente</p>
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Reporte Individual</h3>
                <p class="text-gray-700 mb-4">
                    Genere un reporte PLABASE específico para un paciente seleccionado. 
                    Este reporte incluirá todos los datos de análisis y tratamientos del mes seleccionado.
                </p>
                
                <div class="flex items-center justify-center py-8">
                    <div class="text-center">
                        <x-heroicon-o-user class="w-16 h-16 text-gray-400 mx-auto mb-4" />
                        <p class="text-gray-500">
                            Haga clic en "Generar Reporte por Paciente" en la parte superior 
                            para seleccionar el paciente y el mes.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
