<?php

namespace App\Filament\Resources\PacientesResource\Pages;

use App\Filament\Resources\PacientesResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPacientes extends ViewRecord
{
    protected static string $resource = PacientesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
