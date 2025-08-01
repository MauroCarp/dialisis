<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicacionPaciente extends Model
{
    use HasFactory;

    protected $table = 'medicacionespacientes';

    protected $fillable = [
        'fechamedicacion',
        'id_medicacion',
        'cantidad',
        'id_paciente',
        'observaciones'
    ];

    protected $casts = [
        'fechamedicacion' => 'datetime',
        'cantidad' => 'decimal:2'
    ];

    // Relación con Medicación
    public function medicacion(): BelongsTo
    {
        return $this->belongsTo(Medicacion::class, 'id_medicacion');
    }

    // Relación con Paciente
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }
}
