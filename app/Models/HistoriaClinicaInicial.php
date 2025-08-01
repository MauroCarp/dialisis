<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoriaClinicaInicial extends Model
{
    use HasFactory;

    protected $table = 'historiasclinicasiniciales';

    protected $fillable = [
        'id_paciente',
        'fechahistoriaclinicainicial',
        'contenido'
    ];

    protected $casts = [
        'fechahistoriaclinicainicial' => 'datetime'
    ];

    // Relación con Paciente
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }
}
