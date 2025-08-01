<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'pacientes';

    protected $fillable = [
        'nroalta',
        'nombre',
        'apellido',
        'pesoseco',
        'id_tipodocumento',
        'dnicuitcuil',
        'direccion',
        'telefono',
        'email',
        'fumador',
        'insulinodependiente',
        'fechanacimiento',
        'talla',
        'gruposanguineo',
        'id_localidad',
        'fechaingreso',
        'id_causaingreso',
        'fechaegreso',
        'id_causaegreso'
    ];

    protected $casts = [
        'fechanacimiento' => 'datetime',
        'fechaingreso' => 'datetime',
        'fechaegreso' => 'datetime',
        'fumador' => 'boolean',
        'insulinodependiente' => 'boolean',
        'pesoseco' => 'decimal:2',
        'talla' => 'decimal:2'
    ];

    // Relación con Localidad
    public function localidad(): BelongsTo
    {
        return $this->belongsTo(Localidad::class, 'id_localidad');
    }

    // Relación con Obras Sociales (muchos a muchos a través de tabla pivote)
    public function obrasSociales(): BelongsToMany
    {
        return $this->belongsToMany(ObraSocial::class, 'pacientesobrassociales', 'id_paciente', 'id_obrasocial')
                    ->withPivot('fechavigencia', 'nroafiliado')
                    ->withTimestamps();
    }

    // Relación con Accesos Vasculares
    public function accesosVasculares(): HasMany
    {
        return $this->hasMany(AccesoVascular::class, 'id_paciente');
    }

    // Relación con Patologías (muchos a muchos)
    public function patologias(): BelongsToMany
    {
        return $this->belongsToMany(Patologia::class, 'patologiaspacientes', 'id_paciente', 'id_patologia')
                    ->withTimestamps();
    }

    // Relación con Vacunas (muchos a muchos)
    public function vacunas(): BelongsToMany
    {
        return $this->belongsToMany(Vacuna::class, 'vacunaspacientes', 'id_paciente', 'id_vacuna')
                    ->withPivot('fechavacunacion')
                    ->withTimestamps();
    }
}
