<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medicacion extends Model
{
    use HasFactory;

    protected $table = 'medicaciones';

    protected $fillable = [
        'nombre',
        'id_tipomedicacion',
        'fechabaja'
    ];

    protected $casts = [
        'fechabaja' => 'datetime'
    ];

    // Relación con Tipo de Medicación
    public function tipoMedicacion(): BelongsTo
    {
        return $this->belongsTo(TipoMedicacion::class, 'id_tipomedicacion');
    }

    // Relación con MedicacionPaciente
    public function medicacionesPacientes(): HasMany
    {
        return $this->hasMany(MedicacionPaciente::class, 'id_medicacion');
    }
}
