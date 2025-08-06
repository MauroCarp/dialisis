<?php

namespace App\Filament\Resources\PacientesResource\Pages;

use App\Filament\Resources\PacientesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePacientes extends CreateRecord
{
    protected static string $resource = PacientesResource::class;

    protected function getCancelFormAction(): Actions\Action
    {
        return parent::getCancelFormAction()
            ->label('Volver');
    }
}
