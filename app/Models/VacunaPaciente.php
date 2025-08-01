<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VacunaPaciente extends Model
{
    use HasFactory;

    protected $table = 'vacunaspacientes';

    protected $fillable = [
        'fechavacuna',
        'id_vacuna',
        'id_paciente',
        'observaciones'
    ];

    protected $casts = [
        'fechavacuna' => 'datetime'
    ];

    // Relación con Vacuna
    public function vacuna(): BelongsTo
    {
        return $this->belongsTo(Vacuna::class, 'id_vacuna');
    }

    // Relación con Paciente
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }

    // Relación con Dosis
    public function dosis(): HasMany
    {
        return $this->hasMany(Dosis::class, 'id_vacunapaciente');
    }
}
