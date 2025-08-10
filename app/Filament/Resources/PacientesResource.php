<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PacientesResource\Pages;
use App\Filament\Resources\PacientesResource\RelationManagers;
use App\Models\Paciente;
use App\Models\Localidad;
use App\Models\ObraSocial;
use App\Models\Provincia;
use App\Models\CausaIngreso;
use App\Models\CausaEgreso;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PacientesResource extends Resource
{
    protected static ?string $model = Paciente::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';
    
    protected static ?string $navigationGroup = 'Gestión de Pacientes';
    
    protected static ?string $navigationLabel = 'Pacientes Diálisis';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información Personal')
                    ->schema([
                        Forms\Components\Grid::make(4)
                            ->schema([
                                Forms\Components\TextInput::make('apellido')
                                    ->label('Apellido')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('nombre')
                                    ->label('Nombre')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('dnicuitcuil')
                                    ->label('DNI/CUIT/CUIL')
                                    ->required()
                                    ->maxLength(20),

                                Forms\Components\DatePicker::make('fechanacimiento')
                                    ->label('Fecha de Nacimiento')
                                    ->displayFormat('d/m/Y'),

                                Forms\Components\TextInput::make('telefono')
                                    ->label('Teléfono')
                                    ->maxLength(30),

                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('direccion')
                                    ->label('Dirección')
                                    ->maxLength(255),
                                Forms\Components\Select::make('id_localidad')
                                    ->label('Localidad')
                                    ->options(function () {
                                        return Localidad::orderBy('nombre')
                                            ->pluck('nombre', 'id')
                                            ->toArray();
                                    })
                                    ->searchable()
                                    ->preload(),
                            ]),
                    ]),

                Forms\Components\Section::make('Información Médica')
                    ->schema([
                        Forms\Components\Grid::make(4)
                            ->schema([
                                Forms\Components\TextInput::make('nroalta')
                                    ->label('Número de Alta')
                                    ->numeric(),

                                Forms\Components\TextInput::make('pesoseco')
                                    ->label('Peso Seco (kg)')
                                    ->numeric()
                                    ->step(0.1),

                                Forms\Components\TextInput::make('talla')
                                    ->label('Talla')
                                    ->numeric()
                                    ->step(0.01)
                                    ->inputMode('decimal')
                                    ->minValue(0)
                                    ->maxValue(300)
                                    ->suffix('cm'),

                                Forms\Components\Select::make('gruposanguineo')
                                    ->label('Grupo Sanguíneo')
                                    ->options([
                                        'A+' => 'A+',
                                        'A-' => 'A-',
                                        'B+' => 'B+',
                                        'B-' => 'B-',
                                        'AB+' => 'AB+',
                                        'AB-' => 'AB-',
                                        'O+' => 'O+',
                                        'O-' => 'O-',
                                    ]),

                                Forms\Components\DatePicker::make('fechaingreso')
                                    ->label('Fecha de Ingreso')
                                    ->displayFormat('d/m/Y'),

                                Forms\Components\Select::make('id_causaingreso')
                                    ->label('Causa de Ingreso')
                                    ->options(function () {
                                        return CausaIngreso::orderBy('nombre')
                                            ->pluck('nombre', 'id')
                                            ->toArray();
                                    })
                                    ->searchable()
                                    ->preload(),

                                Forms\Components\DatePicker::make('fechaegreso')
                                    ->label('Fecha de Egreso')
                                    ->displayFormat('d/m/Y'),

                                Forms\Components\Select::make('id_causaegreso')
                                    ->label('Causa de Egreso')
                                    ->options(function () {
                                        return CausaEgreso::orderBy('nombre')
                                            ->pluck('nombre', 'id')
                                            ->toArray();
                                    })
                                    ->searchable()
                                    ->preload(),
                                
                                Forms\Components\Toggle::make('fumador')
                                    ->label('Fumador'),

                                Forms\Components\Toggle::make('insulinodependiente')
                                    ->label('Insulinodependiente'),

                            ]),
                    ]),

                Forms\Components\Section::make('Obras Sociales')
                    ->schema([
                        Forms\Components\Repeater::make('obrasSociales')
                            ->label('Obras Sociales del Paciente')
                            ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\Select::make('id')
                                            ->label('Obra Social')
                                            ->options(
                                                ObraSocial::orderBy('abreviatura')
                                                    ->get()
                                                    ->mapWithKeys(function ($obra) {
                                                        return [$obra->id => "{$obra->abreviatura} - {$obra->descripcion}"];
                                                    })
                                            )
                                            ->searchable()
                                            ->required(),

                                        Forms\Components\TextInput::make('nroafiliado')
                                            ->label('Número de Afiliado')
                                            ->maxLength(50),

                                        Forms\Components\DatePicker::make('fechavigencia')
                                            ->label('Fecha de Vigencia')
                                            ->displayFormat('d/m/Y'),
                                    ]),
                            ])
                            ->collapsible()
                            ->itemLabel(function (array $state): ?string {
                                $obraId = $state['id'] ?? null;
                                if ($obraId) {
                                    $obra = ObraSocial::find($obraId);
                                    return $obra ? $obra->abreviatura : 'Obra Social';
                                }
                                return 'Nueva Obra Social';
                            })
                            ->addActionLabel('Agregar Obra Social')
                            ->defaultItems(0),
                    ]),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['obrasSociales', 'localidad', 'causaIngreso', 'causaEgreso']))
            ->columns([
                    
                Tables\Columns\TextColumn::make('apellido')
                    ->label('Apellido')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('dnicuitcuil')
                    ->label('DNI')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('localidad.nombre')
                    ->label('Localidad')
                    ->sortable()
                    ->searchable(),
            ])
            ->defaultSort('apellido', 'asc')
            ->filters([

            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('Ver')
                ->label('Ver')
                ->icon('heroicon-o-eye')
                ->url(fn ($record) => route('pacientes.show', $record->id)),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPacientes::route('/'),
            'create' => Pages\CreatePacientes::route('/create'),
            'view' => Pages\ViewPacientes::route('/{record}'),
            'edit' => Pages\EditPacientes::route('/{record}/edit'),
        ];
    }
}
