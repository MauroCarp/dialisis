<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalisisDiario extends Model
{
    use HasFactory;

    protected $table = 'analisisdiarios';
    
    public $timestamps = false; // Esta tabla no tiene created_at/updated_at

    protected $fillable = [
        'fechaanalisis',
        'id_paciente',
        'pesopre',
        'pesopost',
        'interdialitico',
        'id_tiposesion',
        'taspre',
        'tadpre',
        'taspos',
        'tadpos',
        'relpesosecopesopre',
        'id_tipofiltro',
        'estado'
    ];

    protected $casts = [
        'fechaanalisis' => 'datetime',
        'pesopre' => 'decimal:2',
        'pesopost' => 'decimal:2',
        'interdialitico' => 'decimal:2',
        'taspre' => 'decimal:1',
        'tadpre' => 'decimal:1',
        'taspos' => 'decimal:1',
        'tadpos' => 'decimal:1',
        'relpesosecopesopre' => 'decimal:4'
    ];

    // Relación con Paciente
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }

    // Relación con Tipo de Sesión
    public function tipoSesion(): BelongsTo
    {
        return $this->belongsTo(TipoSesion::class, 'id_tiposesion');
    }

    // Relación con Tipo de Filtro
    public function tipoFiltro(): BelongsTo
    {
        return $this->belongsTo(TipoFiltro::class, 'id_tipofiltro');
    }

    // Scopes para filtrar por estado
    public function scopePreDialisis($query)
    {
        return $query->where('estado', 'pre_dialisis');
    }

    public function scopePostDialisis($query)
    {
        return $query->where('estado', 'post_dialisis');
    }

    public function scopeCompleto($query)
    {
        return $query->where('estado', 'completo');
    }

    // Métodos para verificar estado
    public function esPreDialisis(): bool
    {
        return $this->estado === 'pre_dialisis';
    }

    public function esPostDialisis(): bool
    {
        return $this->estado === 'post_dialisis';
    }

    public function estaCompleto(): bool
    {
        return $this->estado === 'completo';
    }

    // Método para completar el análisis
    public function completar(): bool
    {
        $this->estado = 'completo';
        return $this->save();
    }
}
