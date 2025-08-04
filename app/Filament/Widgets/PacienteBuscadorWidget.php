<?php

namespace App\Filament\Widgets;

use App\Models\Paciente;
use App\Models\PacienteConsultorio;
use App\Models\TipoAccesoVascular;
use App\Models\Cirujano;
use App\Models\AccesoVascular;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Widgets\Widget;
use Livewire\Component;

class PacienteBuscadorWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.widgets.paciente-buscador-widget';
    
    protected int | string | array $columnSpan = 'full';
    
    public ?array $data = [];
    public $pacienteSeleccionado = null;
    public $tipoTabla = 'hemodialisis'; // 'hemodialisis' o 'consultorio'
    public $resultadosBusqueda = [];
    public $tiposAccesosVasculares = [];
    public $cirujanos = [];
    public $nuevoAcceso = [
        'fechaacceso' => null,
        'id_tipoacceso' => null,
        'id_cirujano' => null,
        'observaciones' => null,
    ];
    
    public function mount(): void
    {
        $this->form->fill();
        $this->loadSelectData();
    }
    
    protected function loadSelectData(): void
    {
        $this->tiposAccesosVasculares = TipoAccesoVascular::whereNull('fechabaja')
            ->orderBy('nombre')
            ->get();
            
        $this->cirujanos = Cirujano::whereNull('fechabaja')
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->get();
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('tipo_tabla')
                    ->label('Tipo de Paciente')
                    ->options([
                        'hemodialisis' => 'Pacientes Hemodiálisis',
                        'consultorio' => 'Pacientes Consultorio',
                    ])
                    ->default('hemodialisis')
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->tipoTabla = $state;
                        $this->limpiarBusqueda();
                    }),
                    
                TextInput::make('termino_busqueda')
                    ->label('Buscar por Nombre, Apellido o DNI/CUIL/CUIT')
                    ->placeholder('Ej: Juan, Pérez, Juan Pérez, 12345678...')
                    ->live(debounce: 500)
                    ->afterStateUpdated(function ($state) {
                        if (strlen($state) >= 3) {
                            $this->buscarPacientes($state);
                        } else {
                            $this->resultadosBusqueda = [];
                            $this->pacienteSeleccionado = null;
                        }
                    })
            ])
            ->statePath('data');
    }
    
    public function buscarPacientes($termino)
    {
        $terminoLower = strtolower(trim($termino));
        
        if ($this->tipoTabla === 'hemodialisis') {
            $this->resultadosBusqueda = Paciente::where(function ($query) use ($terminoLower) {
                // Búsqueda individual por campos
                $query->whereRaw('LOWER(nombre) LIKE ?', ["%{$terminoLower}%"])
                      ->orWhereRaw('LOWER(apellido) LIKE ?', ["%{$terminoLower}%"])
                      ->orWhereRaw('LOWER(dnicuitcuil) LIKE ?', ["%{$terminoLower}%"]);
                
                // Si contiene espacios, buscar como nombre completo
                if (strpos($terminoLower, ' ') !== false) {
                    $query->orWhereRaw('LOWER(CONCAT(nombre, " ", apellido)) LIKE ?', ["%{$terminoLower}%"])
                          ->orWhereRaw('LOWER(CONCAT(apellido, " ", nombre)) LIKE ?', ["%{$terminoLower}%"]);
                }
            })
            ->with(['localidad.provincia', 'tipoDocumento', 'causaIngreso', 'causaEgreso'])
            ->limit(10)
            ->get()
            ->toArray();
        } else {
            $this->resultadosBusqueda = PacienteConsultorio::where(function ($query) use ($terminoLower) {
                // Búsqueda individual por campos
                $query->whereRaw('LOWER(nombre) LIKE ?', ["%{$terminoLower}%"])
                      ->orWhereRaw('LOWER(apellido) LIKE ?', ["%{$terminoLower}%"])
                      ->orWhereRaw('LOWER(dnicuitcuil) LIKE ?', ["%{$terminoLower}%"]);
                
                // Si contiene espacios, buscar como nombre completo
                if (strpos($terminoLower, ' ') !== false) {
                    $query->orWhereRaw('LOWER(CONCAT(nombre, " ", apellido)) LIKE ?', ["%{$terminoLower}%"])
                          ->orWhereRaw('LOWER(CONCAT(apellido, " ", nombre)) LIKE ?', ["%{$terminoLower}%"]);
                }
            })
            ->with(['localidad.provincia', 'tipoDocumento', 'causaIngreso', 'causaEgreso'])
            ->limit(10)
            ->get()
            ->toArray();
        }
    }
    
    public function seleccionarPaciente($pacienteId)
    {
        if ($this->tipoTabla === 'hemodialisis') {
            $this->pacienteSeleccionado = Paciente::with([
                'localidad.provincia',
                'tipoDocumento',
                'causaIngreso',
                'causaEgreso',
                'obrasSociales',
                'patologias',
                'accesosVasculares.tipoAccesoVascular',
                'accesosVasculares.cirujano',
                'analisisDiarios' => function($query) {
                    $query->orderBy('fechaanalisis', 'desc')->limit(5);
                },
                'historiasClinicas' => function($query) {
                    $query->orderBy('fechahistoriaclinica', 'desc')->limit(3);
                },
                'transfusiones' => function($query) {
                    $query->orderBy('fechatransfusion', 'desc')->limit(3);
                },
                'internaciones' => function($query) {
                    $query->with('motivoInternacion')->orderBy('fechainiciointernacion', 'desc')->limit(3);
                }
            ])->find($pacienteId);
        } else {
            $this->pacienteSeleccionado = PacienteConsultorio::with([
                'localidad.provincia',
                'tipoDocumento', 
                'causaIngreso',
                'causaEgreso',
                'historiasClinicasConsultorio' => function($query) {
                    $query->orderBy('fechahistoriaclinica', 'desc')->limit(3);
                }
            ])->find($pacienteId);
        }
        
        $this->resultadosBusqueda = [];
    }
    
    public function limpiarBusqueda()
    {
        $this->resultadosBusqueda = [];
        $this->pacienteSeleccionado = null;
        $this->data['termino_busqueda'] = '';
    }
    
    public function crearAccesoVascular()
    {
        // Validar que hay un paciente seleccionado y es de hemodiálisis
        if (!$this->pacienteSeleccionado || $this->tipoTabla !== 'hemodialisis') {
            session()->flash('error', 'Debe seleccionar un paciente de hemodiálisis.');
            return;
        }

        // Validar los datos del nuevo acceso
        $this->validate([
            'nuevoAcceso.fechaacceso' => 'required|date',
            'nuevoAcceso.id_tipoacceso' => 'required|exists:tiposaccesosvasculares,id',
            'nuevoAcceso.id_cirujano' => 'nullable|exists:cirujanos,id',
            'nuevoAcceso.observaciones' => 'nullable|string|max:1000',
        ], [
            'nuevoAcceso.fechaacceso.required' => 'La fecha del acceso es requerida.',
            'nuevoAcceso.fechaacceso.date' => 'La fecha debe ser válida.',
            'nuevoAcceso.id_tipoacceso.required' => 'Debe seleccionar un tipo de acceso.',
            'nuevoAcceso.id_tipoacceso.exists' => 'El tipo de acceso seleccionado no es válido.',
            'nuevoAcceso.id_cirujano.exists' => 'El cirujano seleccionado no es válido.',
            'nuevoAcceso.observaciones.max' => 'Las observaciones no pueden exceder 1000 caracteres.',
        ]);

        try {
            // Crear el nuevo acceso vascular
            AccesoVascular::create([
                'fechaacceso' => $this->nuevoAcceso['fechaacceso'],
                'id_tipoacceso' => $this->nuevoAcceso['id_tipoacceso'],
                'id_cirujano' => $this->nuevoAcceso['id_cirujano'] ?: null,
                'observaciones' => $this->nuevoAcceso['observaciones'],
                'id_paciente' => $this->pacienteSeleccionado['id'],
            ]);

            // Limpiar el formulario
            $this->nuevoAcceso = [
                'fechaacceso' => null,
                'id_tipoacceso' => null,
                'id_cirujano' => null,
                'observaciones' => null,
            ];

            // Recargar los datos del paciente para mostrar el nuevo acceso
            $this->seleccionarPaciente($this->pacienteSeleccionado['id']);

            session()->flash('success', 'Acceso vascular creado exitosamente.');

            // Emitir evento para cerrar el modal
            $this->dispatch('close-modal');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear el acceso vascular: ' . $e->getMessage());
        }
    }
    
    public static function getSort(): int
    {
        return 1;
    }
}
