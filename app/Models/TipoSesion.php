<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoSesion extends Model
{
    use HasFactory;

    protected $table = 'tipossesiones';

    protected $fillable = [
        'nombre'
    ];

    // Relación con Análisis Diarios
    public function analisisDiarios(): HasMany
    {
        return $this->hasMany(AnalisisDiario::class, 'id_tiposesion');
    }
}
