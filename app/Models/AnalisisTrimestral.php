<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalisisTrimestral extends Model
{
    use HasFactory;

    protected $table = 'analisistrimestrales';
    
    public $timestamps = false; // Esta tabla no tiene created_at/updated_at

    protected $fillable = [
        'protocolo',
        'fechaanalisis',
        'id_paciente',
        'albumina',
        'colesterol',
        'trigliseridos'
    ];

    protected $casts = [
        'fechaanalisis' => 'datetime',
        'albumina' => 'decimal:2',
        'colesterol' => 'decimal:2',
        'trigliseridos' => 'decimal:2'
    ];

    // Relación con Paciente
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }
}
