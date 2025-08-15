<!-- Datos del Paciente -->
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Información Personal Extendida -->
        <div class="bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Personal</h3>
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Nombre Completo:</span>
                        <p class="text-gray-900 font-medium">{{ $paciente->nombre }} {{ $paciente->apellido }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">DNI/CUIT/CUIL:</span>
                        <p class="text-gray-900">{{ $paciente->dnicuitcuil ?? 'No especificado' }}</p>
                    </div>
                    @if($paciente->fechanacimiento)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Fecha de Nacimiento:</span>
                        <p class="text-gray-900">
                            {{ $paciente->fechanacimiento->format('d/m/Y') }}
                            <span class="text-sm text-gray-500">({{ $paciente->fechanacimiento->age }} años)</span>
                        </p>
                    </div>
                    @endif
                    @if($paciente->telefono)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Teléfono:</span>
                        <p class="text-gray-900">{{ $paciente->telefono }}</p>
                    </div>
                    @endif
                    @if($paciente->email)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Email:</span>
                        <p class="text-gray-900">{{ $paciente->email }}</p>
                    </div>
                    @endif
                    @if($paciente->direccion)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Dirección:</span>
                        <p class="text-gray-900">{{ $paciente->direccion }}</p>
                    </div>
                    @endif
                    @if($paciente->localidad)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Localidad:</span>
                        <p class="text-gray-900">{{ $paciente->localidad->nombre ?? 'No especificada' }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Información Médica Extendida -->
        <div class="bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Médica Básica</h3>
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($paciente->fechaingreso)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Fecha de Ingreso:</span>
                        <p class="text-gray-900">{{ \Carbon\Carbon::parse($paciente->fechaingreso)->format('d/m/Y') }}</p>
                    </div>
                    @endif
                    @if($paciente->causaIngreso)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Causa de Ingreso:</span>
                        <p class="text-gray-900">{{ $paciente->causaIngreso->nombre }}</p>
                    </div>
                    @endif
                    @if($paciente->fechaegreso)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Fecha de Egreso:</span>
                        <p class="text-gray-900">{{ \Carbon\Carbon::parse($paciente->fechaegreso)->format('d/m/Y') }}</p>
                    </div>
                    @endif
                    @if($paciente->causaEgreso)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Causa de Egreso:</span>
                        <p class="text-gray-900">{{ $paciente->causaEgreso->nombre }}</p>
                    </div>
                    @endif
                    @if($paciente->derivante)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Derivante:</span>
                        <p class="text-gray-900">{{ $paciente->derivante }}</p>
                    </div>
                    @endif
                    @if($paciente->pesoseco)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Peso Seco:</span>
                        <p class="text-gray-900">{{ $paciente->pesoseco }} kg</p>
                    </div>
                    @endif
                    @if($paciente->talla)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Talla:</span>
                        <p class="text-gray-900">{{ $paciente->talla }} cm</p>
                    </div>
                    @endif
                    @if($paciente->gruposanguineo)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Grupo Sanguíneo:</span>
                        <p class="text-gray-900">{{ $paciente->gruposanguineo }}</p>
                    </div>
                    @endif
                    <div>
                        <span class="text-sm font-medium text-gray-500">Fumador:</span>
                        <p class="text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $paciente->fumador ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $paciente->fumador ? 'Sí' : 'No' }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Insulinodependiente:</span>
                        <p class="text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $paciente->insulinodependiente ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                {{ $paciente->insulinodependiente ? 'Sí' : 'No' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Obras Sociales como sección independiente -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Obras Sociales</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @if($paciente->obrasSociales && $paciente->obrasSociales->count() > 0)
                @foreach($paciente->obrasSociales as $obra)
                    <div class="border border-gray-200 rounded-lg p-4 bg-white">
                        <h4 class="font-medium text-gray-900">{{ $obra->abreviatura }}</h4>
                        <p class="text-sm text-gray-600 mb-2">{{ $obra->descripcion }}</p>
                        @if($obra->pivot && $obra->pivot->nroafiliado)
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Nro. Afiliado:</span> {{ $obra->pivot->nroafiliado }}
                            </p>
                        @endif
                        @if($obra->pivot && $obra->pivot->fechavigencia)
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Fecha Vigencia:</span> {{ \Carbon\Carbon::parse($obra->pivot->fechavigencia)->format('d/m/Y') }}
                            </p>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="col-span-full text-center py-8 text-gray-500">
                    <i class="fas fa-building text-gray-300 text-4xl mb-4"></i>
                    <p>No tiene obras sociales registradas.</p>
                </div>
            @endif
        </div>
    </div>
</div>
