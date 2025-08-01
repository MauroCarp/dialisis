<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalisisSemestral extends Model
{
    use HasFactory;

    protected $table = 'analisissemestrales';

    protected $fillable = [
        'protocolo',
        'fechaanalisis',
        'id_paciente',
        'hbsag',
        'antihbsag',
        'valorantihbsag',
        'antihcv',
        'antihiv',
        'anticore',
        'pth',
        'ferritina',
        'ferremia'
    ];

    protected $casts = [
        'fechaanalisis' => 'datetime',
        'hbsag' => 'boolean',
        'antihbsag' => 'boolean',
        'valorantihbsag' => 'decimal:2',
        'antihcv' => 'boolean',
        'antihiv' => 'boolean',
        'anticore' => 'boolean',
        'pth' => 'decimal:2',
        'ferritina' => 'decimal:2',
        'ferremia' => 'decimal:2'
    ];

    // Relación con Paciente
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }
}
