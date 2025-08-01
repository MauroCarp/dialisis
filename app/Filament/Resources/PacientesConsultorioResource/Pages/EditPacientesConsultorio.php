<?php

namespace App\Filament\Resources\PacientesConsultorioResource\Pages;

use App\Filament\Resources\PacientesConsultorioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPacientesConsultorio extends EditRecord
{
    protected static string $resource = PacientesConsultorioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
