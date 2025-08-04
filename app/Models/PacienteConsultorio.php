<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PacienteConsultorio extends Model
{
    use HasFactory;

    protected $table = 'pacientesconsultorio';

    protected $fillable = [
        'nroalta',
        'nombre',
        'apellido',
        'pesoseco',
        'id_tipodocumento',
        'dnicuitcuil',
        'direccion',
        'telefono',
        'email',
        'fumador',
        'insulinodependiente',
        'fechanacimiento',
        'talla',
        'gruposanguineo',
        'id_localidad',
        'fechaingreso',
        'id_causaingreso',
        'fechaegreso',
        'id_causaegreso',
        'derivante'
    ];

    protected $casts = [
        'fechanacimiento' => 'datetime',
        'fechaingreso' => 'datetime',
        'fechaegreso' => 'datetime',
        'fumador' => 'boolean',
        'insulinodependiente' => 'boolean',
        'pesoseco' => 'decimal:2',
        'talla' => 'decimal:2'
    ];

    // Relación con Localidad
    public function localidad(): BelongsTo
    {
        return $this->belongsTo(Localidad::class, 'id_localidad');
    }

    // Relación con Tipo de Documento
    public function tipoDocumento(): BelongsTo
    {
        return $this->belongsTo(TipoDocumento::class, 'id_tipodocumento');
    }

    // Relación con Causa de Ingreso
    public function causaIngreso(): BelongsTo
    {
        return $this->belongsTo(CausaIngreso::class, 'id_causaingreso');
    }

    // Relación con Causa de Egreso
    public function causaEgreso(): BelongsTo
    {
        return $this->belongsTo(CausaEgreso::class, 'id_causaegreso');
    }

    // Relación con Historias Clínicas de Consultorio
    public function historiasClinicasConsultorio(): HasMany
    {
        return $this->hasMany(HistoriaClinicaConsultorio::class, 'id_paciente');
    }

    // Relación con Obras Sociales (muchos a muchos)
    public function obrasSociales(): BelongsToMany
    {
        return $this->belongsToMany(ObraSocial::class, 'pacientesconsultorioobrassociales', 'id_paciente', 'id_obrasocial')
                    ->withPivot('fechavigencia', 'nroafiliado');
    }
}
