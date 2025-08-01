<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vacuna extends Model
{
    use HasFactory;

    protected $table = 'vacunas';

    protected $fillable = [
        'nombre',
        'fechabaja'
    ];

    protected $casts = [
        'fechabaja' => 'datetime'
    ];

    // Relación con Pacientes (muchos a muchos)
    public function pacientes(): BelongsToMany
    {
        return $this->belongsToMany(Paciente::class, 'vacunaspacientes', 'id_vacuna', 'id_paciente')
                    ->withPivot('fechavacunacion')
                    ->withTimestamps();
    }

    // Relación con VacunaPaciente
    public function vacunasPacientes(): HasMany
    {
        return $this->hasMany(VacunaPaciente::class, 'id_vacuna');
    }
}
