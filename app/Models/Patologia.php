<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Patologia extends Model
{
    use HasFactory;

    protected $table = 'patologias';

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
        return $this->belongsToMany(Paciente::class, 'patologiaspacientes', 'id_patologia', 'id_paciente')
                    ->withTimestamps();
    }
}
