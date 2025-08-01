<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccesoVascular extends Model
{
    use HasFactory;

    protected $table = 'accesosvasculares';
    
    public $timestamps = false; // Esta tabla no tiene created_at/updated_at

    protected $fillable = [
        'fechaacceso',
        'observaciones',
        'id_tipoacceso',
        'id_cirujano',
        'id_paciente'
    ];

    protected $casts = [
        'fechaacceso' => 'datetime'
    ];

    // Relación con Paciente
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }

    // Relación con Tipo de Acceso Vascular
    public function tipoAccesoVascular(): BelongsTo
    {
        return $this->belongsTo(TipoAccesoVascular::class, 'id_tipoacceso');
    }

    // Relación con Cirujano
    public function cirujano(): BelongsTo
    {
        return $this->belongsTo(Cirujano::class, 'id_cirujano');
    }
}
