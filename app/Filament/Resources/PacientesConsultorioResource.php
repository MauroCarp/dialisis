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
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('apellido')
                    ->label('Apellido')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('dnicuitcuil')
                    ->label('DNI/CUIT/CUIL')
                    ->required()
                    ->maxLength(20),

                Forms\Components\DatePicker::make('fecha_nacimiento')
                    ->label('Fecha de Nacimiento'),

                Forms\Components\TextInput::make('telefono')
                    ->label('Teléfono')
                    ->maxLength(20),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->maxLength(255),

                Forms\Components\Textarea::make('direccion')
                    ->label('Dirección')
                    ->maxLength(500),

                Forms\Components\Select::make('obras_sociales')
                    ->label('Obra Social')
                    ->relationship('obrasSociales', 'abreviatura') // Ensure this matches the relationship method name in your model
                    ->preload() // This will load all options for the select
                    ->searchable()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('abreviatura')
                            ->label('Abreviatura')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('descripcion')
                            ->label('Descripción')
                            ->required()
                            ->maxLength(255),
                    ]),

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
