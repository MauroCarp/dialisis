<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Estudio extends Model
{
    use HasFactory;

    protected $table = 'estudios';

    protected $fillable = [
        'nombre',
        'fechabaja'
    ];

    protected $casts = [
        'fechabaja' => 'datetime'
    ];

    // Relación muchos a muchos con Pacientes
    public function pacientes(): BelongsToMany
    {
        return $this->belongsToMany(Paciente::class, 'estudiospacientes', 'id_estudio', 'id_paciente')
                    ->withPivot('fechaestudio', 'observaciones')
                    ->withTimestamps();
    }
}
