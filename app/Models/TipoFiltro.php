<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoFiltro extends Model
{
    use HasFactory;

    protected $table = 'tiposfiltros';

    protected $fillable = [
        'nombre',
        'fechabaja'
    ];

    protected $casts = [
        'fechabaja' => 'datetime'
    ];

    // Relación con Análisis Diarios
    public function analisisDiarios(): HasMany
    {
        return $this->hasMany(AnalisisDiario::class, 'id_tipofiltro');
    }
}
