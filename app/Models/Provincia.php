<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provincia extends Model
{
    use HasFactory;

    protected $table = 'provincias';

    protected $fillable = [
        'nombre',
        'fechabaja'
    ];

    protected $casts = [
        'fechabaja' => 'datetime'
    ];

    // Relación con Localidades
    public function localidades(): HasMany
    {
        return $this->hasMany(Localidad::class, 'id_provincia');
    }
}
