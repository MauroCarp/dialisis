<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PacientesConsultorioResource\Pages;
use App\Filament\Resources\PacientesConsultorioResource\RelationManagers;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                            ->numeric(),
                            
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

                // Agrega aquí otros campos y relaciones según tu modelo
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')->label('Nombre')->sortable(),
                Tables\Columns\TextColumn::make('apellido')->label('Apellido')->sortable(),
                Tables\Columns\TextColumn::make('dnicuitcuil')->label('DNI/CUIT/CUIL')->sortable(),
                Tables\Columns\TextColumn::make('obras_sociales')
                    ->label('Obras Sociales')
                    ->formatStateUsing(function ($record) {
                        $obrasSociales = $record->obrasSociales;
                        if ($obrasSociales->isEmpty()) {
                            return '-';
                        }
                        return $obrasSociales->pluck('abreviatura')->join(', ');
                    })
                    ->default('-'),
            ])
            ->defaultSort('apellido', 'desc')
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListPacientesConsultorios::route('/'),
            'create' => Pages\CreatePacientesConsultorio::route('/create'),
            'edit' => Pages\EditPacientesConsultorio::route('/{record}/edit'),
        ];
    }
}
