<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Paciente - {{ $paciente->nombre }} {{ $paciente->apellido }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            Editar Paciente
                        </h1>
                        <p class="text-gray-600">
                            {{ $paciente->nombre }} {{ $paciente->apellido }}
                        </p>
                    </div>
                    <a href="/admin" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Volver al Admin
                    </a>
                </div>
            </div>

            <!-- Mensajes de éxito/error -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulario -->
            <div class="bg-white shadow rounded-lg p-6">
                <form method="POST" action="{{ route('pacientes.update', $paciente) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Información Personal -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Información Personal</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre *</label>
                                <input type="text" name="nombre" id="nombre" 
                                       value="{{ old('nombre', $paciente->nombre) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="apellido" class="block text-sm font-medium text-gray-700">Apellido *</label>
                                <input type="text" name="apellido" id="apellido" 
                                       value="{{ old('apellido', $paciente->apellido) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="dnicuitcuil" class="block text-sm font-medium text-gray-700">DNI/CUIL/CUIT</label>
                                <input type="text" name="dnicuitcuil" id="dnicuitcuil" 
                                       value="{{ old('dnicuitcuil', $paciente->dnicuitcuil) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="fechanacimiento" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
                                <input type="date" name="fechanacimiento" id="fechanacimiento" 
                                       value="{{ old('fechanacimiento', $paciente->fechanacimiento ? $paciente->fechanacimiento->format('Y-m-d') : '') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                                <input type="text" name="telefono" id="telefono" 
                                       value="{{ old('telefono', $paciente->telefono) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" 
                                       value="{{ old('email', $paciente->email) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div class="md:col-span-2">
                                <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
                                <input type="text" name="direccion" id="direccion" 
                                       value="{{ old('direccion', $paciente->direccion) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Información Médica -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Información Médica</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <label for="pesoseco" class="block text-sm font-medium text-gray-700">Peso Seco (kg)</label>
                                <input type="number" step="0.1" name="pesoseco" id="pesoseco" 
                                       value="{{ old('pesoseco', $paciente->pesoseco) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="talla" class="block text-sm font-medium text-gray-700">Talla (cm)</label>
                                <input type="number" step="0.1" name="talla" id="talla" 
                                       value="{{ old('talla', $paciente->talla) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="gruposanguineo" class="block text-sm font-medium text-gray-700">Grupo Sanguíneo</label>
                                <select name="gruposanguineo" id="gruposanguineo"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Seleccionar...</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $grupo)
                                        <option value="{{ $grupo }}" {{ old('gruposanguineo', $paciente->gruposanguineo) == $grupo ? 'selected' : '' }}>
                                            {{ $grupo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div class="flex items-center">
                                <input type="hidden" name="fumador" value="0">
                                <input type="checkbox" name="fumador" id="fumador" value="1"
                                       {{ old('fumador', $paciente->fumador) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="fumador" class="ml-2 block text-sm text-gray-900">Fumador</label>
                            </div>

                            <div class="flex items-center">
                                <input type="hidden" name="insulinodependiente" value="0">
                                <input type="checkbox" name="insulinodependiente" id="insulinodependiente" value="1"
                                       {{ old('insulinodependiente', $paciente->insulinodependiente) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="insulinodependiente" class="ml-2 block text-sm text-gray-900">Insulinodependiente</label>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex justify-end space-x-4">
                        <a href="/admin" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
