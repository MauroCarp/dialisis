<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'pacientes';
    public $timestamps = false;

    protected $fillable = [
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
        'id_causaegreso'
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

    // Relación con Obras Sociales (muchos a muchos a través de tabla pivote)
    public function obrasSociales(): BelongsToMany
    {
        return $this->belongsToMany(ObraSocial::class, 'pacientesobrassociales', 'id_paciente', 'id_obrasocial')
                    ->withPivot('fechavigencia', 'nroafiliado');
    }

    // Relación con Accesos Vasculares
    public function accesosVasculares(): HasMany
    {
        return $this->hasMany(AccesoVascular::class, 'id_paciente');
    }

    // Relación con Patologías (muchos a muchos)
    public function patologias(): BelongsToMany
    {
        return $this->belongsToMany(Patologia::class, 'patologiaspacientes', 'id_paciente', 'id_patologia')
                    ->withPivot('fechapatologia', 'observaciones');
    }

    // Relación con Vacunas (muchos a muchos)
    public function vacunas(): BelongsToMany
    {
        return $this->belongsToMany(Vacuna::class, 'vacunaspacientes', 'id_paciente', 'id_vacuna')
                    ->withPivot('fechavacuna', 'observaciones');
    }

    // Relación con Análisis Diarios
    public function analisisDiarios(): HasMany
    {
        return $this->hasMany(AnalisisDiario::class, 'id_paciente');
    }

    // Relación con Análisis Mensuales
    public function analisisMensuales(): HasMany
    {
        return $this->hasMany(AnalisisMensual::class, 'id_paciente');
    }

    // Relación con Análisis Trimestrales
    public function analisisTrimestrales(): HasMany
    {
        return $this->hasMany(AnalisisTrimestral::class, 'id_paciente');
    }

    // Relación con Análisis Semestrales
    public function analisisSemestrales(): HasMany
    {
        return $this->hasMany(AnalisisSemestral::class, 'id_paciente');
    }

    // Relación con Historias Clínicas
    public function historiasClinicas(): HasMany
    {
        return $this->hasMany(HistoriaClinica::class, 'id_paciente')->orderBy('fechahistoriaclinica', 'desc');
    }

    // Relación con Historias Clínicas Iniciales
    public function historiasClinicasIniciales(): HasMany
    {
        return $this->hasMany(HistoriaClinicaInicial::class, 'id_paciente');
    }

    // Relación con Transfusiones
    public function transfusiones(): HasMany
    {
        return $this->hasMany(Transfusion::class, 'id_paciente')->orderBy('fechatransfusion', 'desc');
    }

    // Relación con Internaciones
    public function internaciones(): HasMany
    {
        return $this->hasMany(Internacion::class, 'id_paciente');
    }

    // Relación muchos a muchos con Estudios
    public function estudios(): BelongsToMany
    {
        return $this->belongsToMany(Estudio::class, 'estudiospacientes', 'id_paciente', 'id_estudio')
                    ->withPivot('fechaestudio', 'observaciones');
    }

    // Relación con Estudios de Pacientes (registros individuales)
    public function estudiosPacientes(): HasMany
    {
        return $this->hasMany(EstudioPaciente::class, 'id_paciente')->orderBy('fechaestudio', 'desc');
    }

    // Relación con Patologías de Pacientes (registros individuales)
    public function patologiasPacientes(): HasMany
    {
        return $this->hasMany(PatologiaPaciente::class, 'id_paciente')->orderBy('fechapatologia', 'desc');
    }

    // Relación con Antecedentes Personales
    public function antecedentesPersonales(): HasMany
    {
        return $this->hasMany(AntecedentePersonal::class, 'id_paciente');
    }

    // Relación con Antecedentes Familiares
    public function antecedentesFamiliares(): HasMany
    {
        return $this->hasMany(AntecedenteFamiliar::class, 'id_paciente');
    }

    // Relación con Medicaciones a través de tabla pivote
    public function medicacionesPacientes(): HasMany
    {
        return $this->hasMany(MedicacionPaciente::class, 'id_paciente')->orderBy('fechamedicacion', 'desc');
    }

    // Relación con Vacunas a través de tabla pivote
    public function vacunasPacientes(): HasMany
    {
        return $this->hasMany(VacunaPaciente::class, 'id_paciente')->orderBy('fechavacuna', 'desc');
    }
}
