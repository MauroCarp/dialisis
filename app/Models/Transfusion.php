<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transfusion extends Model
{
    use HasFactory;

    protected $table = 'transfusiones';
    
    public $timestamps = false; // Esta tabla no tiene created_at/updated_at

    protected $fillable = [
        'id_paciente',
        'fechatransfusion',
        'observaciones'
    ];

    protected $casts = [
        'fechatransfusion' => 'datetime'
    ];

    // Relación con Paciente (dialisis)
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }

    // Relación con Paciente Consultorio
    public function pacienteConsultorio(): BelongsTo
    {
        return $this->belongsTo(PacienteConsultorio::class, 'id_paciente');
    }
}
