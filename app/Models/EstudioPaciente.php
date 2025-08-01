<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstudioPaciente extends Model
{
    use HasFactory;

    protected $table = 'estudiospacientes';
    public $timestamps = false;

    protected $fillable = [
        'fechaestudio',
        'id_estudio',
        'id_paciente',
        'observaciones'
    ];

    protected $casts = [
        'fechaestudio' => 'datetime'
    ];

    // Relación con Estudio
    public function estudio(): BelongsTo
    {
        return $this->belongsTo(Estudio::class, 'id_estudio');
    }

    // Relación con Paciente
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }
}
