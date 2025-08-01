<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MotivoInternacion extends Model
{
    use HasFactory;

    protected $table = 'motivosinternaciones';

    protected $fillable = [
        'nombre',
        'fechabaja'
    ];

    protected $casts = [
        'fechabaja' => 'datetime'
    ];

    // Relación con Internaciones
    public function internaciones(): HasMany
    {
        return $this->hasMany(Internacion::class, 'id_motivo_internacion');
    }
}
