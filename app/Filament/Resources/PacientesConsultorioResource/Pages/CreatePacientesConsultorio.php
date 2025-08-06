<?php

namespace App\Filament\Resources\PacientesConsultorioResource\Pages;

use App\Filament\Resources\PacientesConsultorioResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePacientesConsultorio extends CreateRecord
{
    protected static string $resource = PacientesConsultorioResource::class;

    public function getTitle(): string
    {
        return 'Nuevo Paciente Consultorio';
    }

    protected function getCancelFormAction(): Actions\Action
    {
        return parent::getCancelFormAction()
            ->label('Volver');
    }
}
