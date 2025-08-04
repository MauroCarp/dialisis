<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Historia Clínica - {{ $paciente->nombre }} {{ $paciente->apellido }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            Nueva Historia Clínica
                        </h1>
                        <p class="text-gray-600 mt-1">
                            Paciente: {{ $paciente->nombre }} {{ $paciente->apellido }}
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                Consultorio
                            </span>
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('pacientes.show', $paciente->id) }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Volver
                        </a>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <div class="bg-white shadow rounded-lg p-6">
                <form method="POST" action="{{ route('historias-clinicas-consultorio.store', $paciente->id) }}">
                    @csrf
                    
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Fecha -->
                        <div>
                            <label for="fechahistoriaclinica" class="block text-sm font-medium text-gray-700">
                                Fecha de Historia Clínica
                            </label>
                            <input type="datetime-local" 
                                   id="fechahistoriaclinica" 
                                   name="fechahistoriaclinica" 
                                   value="{{ old('fechahistoriaclinica', now()->format('Y-m-d\TH:i')) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('fechahistoriaclinica') border-red-500 @enderror">
                            @error('fechahistoriaclinica')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Observaciones -->
                        <div>
                            <label for="observaciones" class="block text-sm font-medium text-gray-700">
                                Observaciones
                            </label>
                            <textarea id="observaciones" 
                                      name="observaciones" 
                                      rows="8"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('observaciones') border-red-500 @enderror"
                                      placeholder="Escriba las observaciones de la historia clínica...">{{ old('observaciones') }}</textarea>
                            @error('observaciones')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('pacientes.show', $paciente->id) }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Guardar Historia Clínica
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
