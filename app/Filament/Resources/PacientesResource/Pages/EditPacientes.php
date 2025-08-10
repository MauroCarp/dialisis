<?php

namespace App\Filament\Resources\PacientesResource\Pages;

use App\Filament\Resources\PacientesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPacientes extends EditRecord
{
    protected static string $resource = PacientesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getCancelFormAction(): Actions\Action
    {
        return parent::getCancelFormAction()
            ->label('Volver');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Cargar las obras sociales con sus datos pivot
        $this->record->load('obrasSociales');
        
        $data['obrasSociales'] = $this->record->obrasSociales->map(function ($obraSocial) {
            return [
                'id' => $obraSocial->id,
                'nroafiliado' => $obraSocial->pivot->nroafiliado,
                'fechavigencia' => $obraSocial->pivot->fechavigencia,
            ];
        })->toArray();

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Separar los datos de obras sociales
        $obrasSociales = $data['obrasSociales'] ?? [];
        unset($data['obrasSociales']);

        // Actualizar el registro principal
        $record->update($data);

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

        $record->obrasSociales()->sync($syncData);

        return $record;
    }
}
