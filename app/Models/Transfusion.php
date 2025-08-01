<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transfusion extends Model
{
    use HasFactory;

    protected $table = 'transfusiones';

    protected $fillable = [
        'id_paciente',
        'fechatransfusion',
        'observaciones'
    ];

    protected $casts = [
        'fechatransfusion' => 'datetime'
    ];

    // Relación con Paciente
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }
}
