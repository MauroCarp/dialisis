<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PacienteObraSocial extends Model
{
    use HasFactory;

    protected $table = 'pacientesobrassociales';
    public $timestamps = false;

    protected $fillable = [
        'fechavigencia',
        'id_obrasocial',
        'nroafiliado',
        'id_paciente'
    ];

    protected $casts = [
        'fechavigencia' => 'datetime'
    ];

    // Relación con Paciente
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }

    // Relación con Obra Social
    public function obraSocial(): BelongsTo
    {
        return $this->belongsTo(ObraSocial::class, 'id_obrasocial');
    }
}
