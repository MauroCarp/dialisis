<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoriaClinicaConsultorio extends Model
{
    use HasFactory;

    protected $table = 'historiasclinicasconsultorio';

    protected $fillable = [
        'id_paciente',
        'fechahistoriaclinica',
        'observaciones'
    ];

    protected $casts = [
        'fechahistoriaclinica' => 'datetime'
    ];

    // Relación con Paciente de Consultorio
    public function pacienteConsultorio(): BelongsTo
    {
        return $this->belongsTo(PacienteConsultorio::class, 'id_paciente');
    }
}
