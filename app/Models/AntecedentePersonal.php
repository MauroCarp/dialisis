<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AntecedentePersonal extends Model
{
    use HasFactory;

    protected $table = 'antecedentespersonales';

    protected $fillable = [
        'id_paciente',
        'fechaantecedente',
        'observaciones'
    ];

    protected $casts = [
        'fechaantecedente' => 'datetime'
    ];

    // Relación con Paciente
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }
}
