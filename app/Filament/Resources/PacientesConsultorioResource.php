<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PacientesConsultorioResource\Pages;
use App\Filament\Resources\PacientesConsultorioResource\RelationManagers;
use App\Models\ObraSocial;
use App\Models\PacienteConsultorio;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PacientesConsultorioResource extends Resource
{
    protected static ?string $model = PacienteConsultorio::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    
    protected static ?string $navigationGroup = 'Gestión de Pacientes';
    
    protected static ?string $navigationLabel = 'Pacientes Consultorio';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información Personal')
                    ->schema([
                        Forms\Components\TextInput::make('nroalta')
                            ->label('Nro. de Alta')
                            ->maxLength(20),

                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('apellido')
                            ->label('Apellido')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('fechanacimiento')
                            ->label('Fecha de Nacimiento'),
                        Forms\Components\Select::make('id_tipodocumento')
                            ->label('Tipo de Documento')
                            ->relationship('tipoDocumento', 'descripcion')
                            ->required()
                            ->preload()
                            ->searchable(),
                        Forms\Components\TextInput::make('dnicuitcuil')
                            ->label('DNI/CUIT/CUIL')
                            ->required()
                            ->maxLength(20),
                        
                        Forms\Components\Grid::make(4)
                            ->schema([
                                Forms\Components\TextInput::make('telefono')
                                    ->label('Teléfono')
                                    ->maxLength(20),

                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('direccion')
                                    ->label('Dirección')
                                    ->maxLength(100),

                                Forms\Components\Select::make('id_localidad')
                                    ->label('Localidad')
                                    ->relationship('localidad', 'nombre')
                                    ->required()
                                    ->preload()
                                    ->searchable(),
                            ]),
                    ])->columns(3),

                Forms\Components\Section::make('Información Médica')
                    ->schema([
                        Forms\Components\DatePicker::make('fechaingreso')
                            ->label('Fecha de Ingreso'),

                        Forms\Components\Select::make('id_causaIngreso')
                            ->label('Causa de Ingreso')
                            ->relationship('causaIngreso', 'nombre')
                            ->preload()
                            ->searchable(),
                        Forms\Components\TextInput::make('derivante')
                            ->label('Derivante')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('pesoseco')
                            ->label('Peso Seco')
                            ->numeric(),
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
                            ])
                            ->required(),
                        Forms\Components\Grid::make(4)
                            ->schema([
                                Forms\Components\Toggle::make('fumador')
                                    ->label('Fumador'),

                                Forms\Components\Toggle::make('insulinodependiente')
                                    ->label('Insulinodependiente'),

                                Forms\Components\DatePicker::make('fechaegreso')
                                    ->label('Fecha de Egreso'),

                                Forms\Components\Select::make('id_causaegreso')
                                    ->label('Causa de Egreso')
                                    ->relationship('causaEgreso', 'nombre')
                                    ->preload()
                                    ->searchable(),
                            ]),

                        
                    ])->columns(3),

                
                    Forms\Components\Section::make('Obras Sociales')
                    ->schema([
                        Forms\Components\Repeater::make('obrasSociales')
                            ->label('Obras Sociales del Paciente')
                            ->relationship()
                            ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\Select::make('id')
                                            ->label('Obra Social')
                                            ->options(
                                                ObraSocial::whereNull('fechaBaja')
                                                    ->orderBy('abreviatura')
                                                    ->get()
                                                    ->mapWithKeys(function ($obra) {
                                                        return [$obra->id => "{$obra->abreviatura} - {$obra->descripcion}"];
                                                    })
                                            )
                                            ->searchable()
                                            ->required(),

                                        Forms\Components\TextInput::make('pivot.nroafiliado')
                                            ->label('Número de Afiliado')
                                            ->maxLength(50),

                                        Forms\Components\DatePicker::make('pivot.fechavigencia')
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nroalta')
                    ->label('Nro. Alta')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('apellido')
                    ->label('Apellido')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dnicuitcuil')
                    ->label('DNI/CUIT/CUIL')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->searchable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('localidad.nombre')
                    ->label('Localidad')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('fechaingreso')
                    ->label('Fecha Ingreso')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('fumador')
                    ->label('Fumador')
                    ->boolean()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\IconColumn::make('insulinodependiente')
                    ->label('Insulino Dep.')
                    ->boolean()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('obras_sociales')
                    ->label('Obras Sociales')
                    ->formatStateUsing(function ($record) {
                        $obrasSociales = $record->obrasSociales;
                        if ($obrasSociales->isEmpty()) {
                            return '-';
                        }
                        return $obrasSociales->pluck('abreviatura')->join(', ');
                    })
                    ->searchable(query: function ($query, $search) {
                        return $query->whereHas('obrasSociales', function ($query) use ($search) {
                            $query->where('abreviatura', 'like', "%{$search}%")
                                  ->orWhere('descripcion', 'like', "%{$search}%");
                        });
                    })
                    ->default('-'),
                Tables\Columns\TextColumn::make('causaIngreso.nombre')
                    ->label('Causa Ingreso')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->wrap(),
                Tables\Columns\TextColumn::make('causaEgreso.nombre')
                    ->label('Causa Egreso')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->wrap(),
            ])
            ->defaultSort('apellido', 'asc')
            ->searchable()
            ->filters([
                Tables\Filters\SelectFilter::make('id_localidad')
                    ->label('Localidad')
                    ->relationship('localidad', 'nombre')
                    ->searchable()
                    ->preload(),
                    
                Tables\Filters\SelectFilter::make('obras_sociales')
                    ->label('Obra Social')
                    ->query(function ($query, array $data) {
                        if (isset($data['value']) && $data['value'] !== '') {
                            return $query->whereHas('obrasSociales', function ($query) use ($data) {
                                $query->where('id', $data['value']);
                            });
                        }
                        return $query;
                    })
                    ->options(
                        ObraSocial::whereNull('fechaBaja')
                            ->orderBy('abreviatura')
                            ->get()
                            ->mapWithKeys(function ($obra) {
                                return [$obra->id => $obra->abreviatura . ' - ' . $obra->descripcion];
                            })
                    )
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('con_obras_sociales')
                    ->label('Con Obras Sociales')
                    ->query(fn ($query) => $query->whereHas('obrasSociales')),

                Tables\Filters\Filter::make('sin_obras_sociales')
                    ->label('Sin Obras Sociales')
                    ->query(fn ($query) => $query->whereDoesntHave('obrasSociales')),

                Tables\Filters\TernaryFilter::make('fumador')
                    ->label('Fumador')
                    ->nullable(),

                Tables\Filters\TernaryFilter::make('insulinodependiente')
                    ->label('Insulinodependiente')
                    ->nullable(),

                Tables\Filters\Filter::make('fecha_ingreso')
                    ->label('Fecha de Ingreso')
                    ->form([
                        Forms\Components\DatePicker::make('desde')
                            ->label('Desde'),
                        Forms\Components\DatePicker::make('hasta')
                            ->label('Hasta'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['desde'], fn ($query) => $query->whereDate('fechaingreso', '>=', $data['desde']))
                            ->when($data['hasta'], fn ($query) => $query->whereDate('fechaingreso', '<=', $data['hasta']));
                    }),

                Tables\Filters\Filter::make('activos')
                    ->label('Pacientes Activos')
                    ->query(fn ($query) => $query->whereNull('fechaegreso')),

                Tables\Filters\Filter::make('egresados')
                    ->label('Pacientes Egresados')
                    ->query(fn ($query) => $query->whereNotNull('fechaegreso')),
            ])
            ->actions([
                Tables\Actions\Action::make('Ver')
                ->label('Ver')
                ->icon('heroicon-o-eye')
                ->url(fn ($record) => route('pacientes.show', ['paciente' => $record->id, 'tipo' => 'consultorio'])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['localidad', 'obrasSociales', 'causaIngreso', 'causaEgreso']);
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
            'index' => Pages\ListPacientesConsultorios::route('/'),
            'create' => Pages\CreatePacientesConsultorio::route('/create'),
            'edit' => Pages\EditPacientesConsultorio::route('/{record}/edit'),
        ];
    }
}
