<?php

namespace App\Filament\Widgets;

use App\Models\AnalisisDiario;
use App\Models\TipoSesion;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Widgets\Widget;
use Livewire\Component;

class AnalisisPendientesWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.widgets.analisis-pendientes-widget';
    
    protected int | string | array $columnSpan = 'full';
    
    public ?array $data = [];
    public $analisisPendientes = [];
    public $analisisSeleccionado = null;
    public $mostrarModal = false;
    public $tiposSesiones = [];
    
    public function mount(): void
    {
        // Verificar si el usuario logueado NO es 'Lili'
        $user = auth()->user();
        if ($user && $user->email === 'liliana.monje@chcdg.com') {
            // No mostrar el widget para el usuario 'Lili'
            return;
        }
        
        $this->cargarAnalisisPendientes();
        $this->cargarSelectData();
    }
    
    protected function cargarAnalisisPendientes(): void
    {
        $this->analisisPendientes = AnalisisDiario::where('estado', '!=', 'completo')
            ->with(['paciente', 'tipoFiltro'])
            ->orderBy('fechaanalisis', 'desc')
            ->limit(20) // Limitar a los últimos 20 para no sobrecargar
            ->get()
            ->toArray();
    }
    
    protected function cargarSelectData(): void
    {
        $this->tiposSesiones = TipoSesion::orderBy('nombre')->get();
    }
    
    public function abrirModal($analisisId)
    {
        $analisis = AnalisisDiario::with(['paciente', 'tipoFiltro'])
            ->find($analisisId);
            
        if ($analisis) {
            $this->analisisSeleccionado = $analisis->toArray();
            $this->mostrarModal = true;
            
            // Llenar el formulario con los datos existentes (si los hay)
            $this->form->fill([
                'pesopost' => $analisis->pesopost,
                'taspos' => $analisis->taspos,
                'tadpos' => $analisis->tadpos,
                'id_tiposesion' => $analisis->id_tiposesion,
                'observaciones' => $analisis->observaciones,
            ]);
        }
    }
    
    public function cerrarModal()
    {
        $this->mostrarModal = false;
        $this->analisisSeleccionado = null;
        $this->form->fill([]);
    }
    
    public function completarAnalisis()
    {
        if (!$this->analisisSeleccionado) {
            return;
        }
        
        $validatedData = $this->form->getState();
        
        // Validar que los campos requeridos estén completos
        $this->validate([
            'data.pesopost' => 'required|numeric|min:0|max:500',
            'data.taspos' => 'required|numeric|min:0|max:300',
            'data.tadpos' => 'required|numeric|min:0|max:200',
        ]);
        
        try {
            $analisis = AnalisisDiario::find($this->analisisSeleccionado['id']);
            
            if ($analisis) {
                $analisis->update([
                    'pesopost' => $validatedData['pesopost'],
                    'taspos' => $validatedData['taspos'],
                    'tadpos' => $validatedData['tadpos'],
                    'estado' => 'completo'
                ]);
                
                // Recargar la lista
                $this->cargarAnalisisPendientes();
                
                // Cerrar modal
                $this->cerrarModal();
                
                // Mostrar mensaje de éxito
                $this->dispatch('notify', [
                    'type' => 'success',
                    'title' => 'Análisis Completado',
                    'message' => 'El análisis diario ha sido completado exitosamente.'
                ]);
            }
        } catch (\Exception $e) {
            // Mostrar mensaje de error
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Error al completar el análisis: ' . $e->getMessage()
            ]);
        }
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('pesopost')
                    ->label('Peso Post (kg)')
                    ->numeric()
                    ->step(0.1)
                    ->minValue(0)
                    ->maxValue(500)
                    ->required(),
                    
                TextInput::make('taspos')
                    ->label('TAS Post')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(300)
                    ->required(),
                    
                TextInput::make('tadpos')
                    ->label('TAD Post')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(200)
                    ->required(),
                    
            ])
            ->statePath('data')
            ->columns(3); // Tres columnas para mostrar los campos lado a lado
    }
    
    // Método para verificar si el widget debe mostrarse
    public static function shouldShow(): bool
    {
        $user = auth()->user();
        return $user && $user->email !== 'liliana.monje@chcdg.com';
    }
    
    public static function getSort(): int
    {
        return 2; // Se mostrará después del buscador de pacientes
    }
}
