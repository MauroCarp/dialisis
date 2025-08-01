<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Internacion extends Model
{
    use HasFactory;

    protected $table = 'internaciones';
    
    public $timestamps = false; // Esta tabla no tiene created_at/updated_at

    protected $fillable = [
        'id_paciente',
        'fechainiciointernacion',
        'fechafininternacion',
        'observaciones',
        'id_motivo_internacion'
    ];

    protected $casts = [
        'fechainiciointernacion' => 'datetime',
        'fechafininternacion' => 'datetime'
    ];

    // Relación con Paciente
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }

    // Relación con Motivo de Internación
    public function motivoInternacion(): BelongsTo
    {
        return $this->belongsTo(MotivoInternacion::class, 'id_motivo_internacion');
    }
}
