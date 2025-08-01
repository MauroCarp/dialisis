<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoMedicacion extends Model
{
    use HasFactory;

    protected $table = 'tiposmedicaciones';

    protected $fillable = [
        'nombre',
        'fechabaja'
    ];

    protected $casts = [
        'fechabaja' => 'datetime'
    ];

    // Relación con Medicaciones
    public function medicaciones(): HasMany
    {
        return $this->hasMany(Medicacion::class, 'id_tipomedicacion');
    }
}
