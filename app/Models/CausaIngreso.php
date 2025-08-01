<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CausaIngreso extends Model
{
    use HasFactory;

    protected $table = 'causasingresos';

    protected $fillable = [
        'descripcion',
        'fechabaja'
    ];

    protected $casts = [
        'fechabaja' => 'datetime'
    ];

    // Relación con Pacientes
    public function pacientes(): HasMany
    {
        return $this->hasMany(Paciente::class, 'id_causaingreso');
    }
}
