<?php

namespace App\Filament\Resources\PacientesResource\Pages;

use App\Filament\Resources\PacientesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePacientes extends CreateRecord
{
    protected static string $resource = PacientesResource::class;

    protected function getCancelFormAction(): Actions\Action
    {
        return parent::getCancelFormAction()
            ->label('Volver');
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Separar los datos de obras sociales
        $obrasSociales = $data['obrasSociales'] ?? [];
        unset($data['obrasSociales']);

        // Crear el registro principal
        $record = static::getModel()::create($data);

        // Manejar las obras sociales
        $syncData = [];
        foreach ($obrasSociales as $obraSocial) {
            if (isset($obraSocial['id'])) {
                $syncData[$obraSocial['id']] = [
                    'nroafiliado' => $obraSocial['nroafiliado'] ?? null,
                    'fechavigencia' => $obraSocial['fechavigencia'] ?? null,
                ];
            }
        }

        if (!empty($syncData)) {
            $record->obrasSociales()->sync($syncData);
        }

        return $record;
    }
}
