<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dosis extends Model
{
    use HasFactory;

    protected $table = 'dosis';
    
    public $timestamps = false; // Desactivar timestamps automáticos

    protected $fillable = [
        'fechadosis',
        'numero',
        'cantidad',
        'id_vacunapaciente'
    ];

    protected $casts = [
        'fechadosis' => 'datetime',
        'cantidad' => 'decimal:2'
    ];

    // Relación con VacunaPaciente
    public function vacunaPaciente(): BelongsTo
    {
        return $this->belongsTo(VacunaPaciente::class, 'id_vacunapaciente');
    }
}
