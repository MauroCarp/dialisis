<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalisisMensual extends Model
{
    use HasFactory;

    protected $table = 'analisismensuales';

    protected $fillable = [
        'protocolo',
        'fechaanalisis',
        'id_paciente',
        'hematocrito',
        'hemoglobina',
        'rto_blancos',
        'rto_rojos',
        'rto_plaquetas',
        'creatinina',
        'uremia_pre',
        'uremia_post',
        'urea_creatinina',
        'rpu',
        'ktv_daugiras',
        'ktv_basile',
        'tac_urea',
        'pcr',
        'sodio',
        'potasio',
        'calcemia',
        'gpt',
        'got',
        'fosfatemia',
        'fosfatasa_alcalina'
    ];

    protected $casts = [
        'fechaanalisis' => 'datetime',
        'hematocrito' => 'decimal:2',
        'hemoglobina' => 'decimal:2',
        'rto_blancos' => 'decimal:2',
        'rto_rojos' => 'decimal:2',
        'rto_plaquetas' => 'decimal:2',
        'creatinina' => 'decimal:2',
        'uremia_pre' => 'decimal:2',
        'uremia_post' => 'decimal:2',
        'urea_creatinina' => 'decimal:2',
        'rpu' => 'decimal:2',
        'ktv_daugiras' => 'decimal:2',
        'ktv_basile' => 'decimal:2',
        'tac_urea' => 'decimal:2',
        'pcr' => 'decimal:2',
        'sodio' => 'decimal:2',
        'potasio' => 'decimal:2',
        'calcemia' => 'decimal:2',
        'gpt' => 'decimal:2',
        'got' => 'decimal:2',
        'fosfatemia' => 'decimal:2',
        'fosfatasa_alcalina' => 'decimal:2'
    ];

    // Relación con Paciente
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }
}
