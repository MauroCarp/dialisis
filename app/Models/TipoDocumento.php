<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoDocumento extends Model
{
    use HasFactory;

    protected $table = 'tiposdocumentos';

    protected $fillable = [
        'abreviatura',
        'descripcion',
        'fechabaja'
    ];

    protected $casts = [
        'fechabaja' => 'datetime'
    ];

    // Relación con Pacientes
    public function pacientes(): HasMany
    {
        return $this->hasMany(Paciente::class, 'id_tipodocumento');
    }

    // Relación con Empleados
    public function empleados(): HasMany
    {
        return $this->hasMany(Empleado::class, 'id_tipodocumento');
    }
}
