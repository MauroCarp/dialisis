<?php

namespace App\Filament\Resources\PacientesConsultorioResource\Pages;

use App\Filament\Resources\PacientesConsultorioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPacientesConsultorios extends ListRecords
{
    protected static string $resource = PacientesConsultorioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Nuevo Paciente Consultorio'),
        ];
    }

}
