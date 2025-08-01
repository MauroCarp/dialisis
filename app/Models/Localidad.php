<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Localidad extends Model
{
    use HasFactory;

    protected $table = 'localidades';

    protected $fillable = [
        'nombre',
        'codpostal',
        'id_provincia',
        'fechabaja'
    ];

    protected $casts = [
        'fechabaja' => 'datetime'
    ];

    // Relación con Provincia
    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class, 'id_provincia');
    }

    // Relación con Pacientes
    public function pacientes(): HasMany
    {
        return $this->hasMany(Paciente::class, 'id_localidad');
    }
}
