<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ObraSocial extends Model
{
    use HasFactory;

    protected $table = 'obrassociales';

    protected $fillable = [
        'abreviatura',
        'descripcion',
        'fechaBaja'
    ];

    protected $casts = [
        'fechaBaja' => 'datetime'
    ];

    // Relación con Pacientes (muchos a muchos)
    public function pacientes(): BelongsToMany
    {
        return $this->belongsToMany(Paciente::class, 'pacientesobrassociales', 'id_obrasocial', 'id_paciente')
                    ->withPivot('fechavigencia', 'nroafiliado')
                    ->withTimestamps();
    }
}
