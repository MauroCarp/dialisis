<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoAccesoVascular extends Model
{
    use HasFactory;

    protected $table = 'tiposaccesosvasculares';

    protected $fillable = [
        'nombre',
        'fechabaja'
    ];

    protected $casts = [
        'fechabaja' => 'datetime'
    ];

    // Relación con Accesos Vasculares
    public function accesosVasculares(): HasMany
    {
        return $this->hasMany(AccesoVascular::class, 'id_tipoacceso');
    }
}
