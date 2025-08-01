<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatologiaPaciente extends Model
{
    use HasFactory;

    protected $table = 'patologiaspacientes';
    public $timestamps = false;

    protected $fillable = [
        'fechapatologia',
        'id_patologia',
        'id_paciente',
        'observaciones'
    ];

    protected $casts = [
        'fechapatologia' => 'datetime'
    ];

    // Relación con Patología
    public function patologia(): BelongsTo
    {
        return $this->belongsTo(Patologia::class, 'id_patologia');
    }

    // Relación con Paciente
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }
}
