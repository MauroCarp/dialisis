# Modelos de Eloquent - Sistema de Hemodiálisis

## Resumen de Modelos Creados y Actualizados

### Modelos Base Existentes (Actualizados)
- `Paciente` - Pacientes del sistema de hemodiálisis
- `Localidad` - Localidades/ciudades
- `Provincia` - Provincias
- `ObraSocial` - Obras sociales
- `Patologia` - Patologías
- `Vacuna` - Vacunas
- `Medicacion` - Medicaciones
- `TipoMedicacion` - Tipos de medicación
- `AccesoVascular` - Accesos vasculares
- `TipoAccesoVascular` - Tipos de acceso vascular
- `User` - Usuarios del sistema

### Modelos Nuevos Creados

#### Tipos de Documentos y Causas
- `TipoDocumento` - Tipos de documento (DNI, CUIL, etc.)
- `CausaIngreso` - Causas de ingreso de pacientes
- `CausaEgreso` - Causas de egreso de pacientes

#### Análisis Médicos
- `AnalisisDiario` - Análisis diarios de pacientes
- `AnalisisMensual` - Análisis mensuales con laboratorio completo
- `AnalisisTrimestral` - Análisis trimestrales (albumina, colesterol, etc.)
- `AnalisisSemestral` - Análisis semestrales (hepatitis, HIV, etc.)

#### Tipos de Sesiones y Filtros
- `TipoSesion` - Tipos de sesión de diálisis
- `TipoFiltro` - Tipos de filtros utilizados

#### Personal Médico
- `Empleado` - Empleados del centro
- `Rol` - Roles de empleados
- `Cirujano` - Cirujanos que realizan accesos vasculares

#### Historias Clínicas
- `HistoriaClinica` - Historias clínicas regulares
- `HistoriaClinicaConsultorio` - Historias clínicas de consultorio
- `HistoriaClinicaInicial` - Historias clínicas iniciales

#### Pacientes Especializados
- `PacienteConsultorio` - Pacientes de consultorio externo

#### Antecedentes
- `AntecedentePersonal` - Antecedentes personales de pacientes
- `AntecedenteFamiliar` - Antecedentes familiares de pacientes

#### Procedimientos
- `Transfusion` - Transfusiones de sangre
- `Internacion` - Internaciones hospitalarias
- `MotivoInternacion` - Motivos de internación

#### Estudios y Medicaciones
- `Estudio` - Estudios médicos
- `EstudioPaciente` - Relación estudios-pacientes
- `MedicacionPaciente` - Medicaciones administradas a pacientes
- `VacunaPaciente` - Vacunas aplicadas a pacientes
- `Dosis` - Dosis de vacunas

## Relaciones Principales

### Paciente (Modelo Central)
```php
// Relaciones directas
- tipoDocumento() : BelongsTo
- localidad() : BelongsTo  
- causaIngreso() : BelongsTo
- causaEgreso() : BelongsTo

// Relaciones uno a muchos
- analisisDiarios() : HasMany
- analisisMensuales() : HasMany
- analisisTrimestrales() : HasMany
- analisisSemestrales() : HasMany
- historiasClinicas() : HasMany
- historiasClinicasIniciales() : HasMany
- accesosVasculares() : HasMany
- transfusiones() : HasMany
- internaciones() : HasMany
- antecedentesPersonales() : HasMany
- antecedentesFamiliares() : HasMany
- medicacionesPacientes() : HasMany
- vacunasPacientes() : HasMany

// Relaciones muchos a muchos
- obrasSociales() : BelongsToMany
- patologias() : BelongsToMany
- vacunas() : BelongsToMany
- estudios() : BelongsToMany
```

### Localidad
```php
- provincia() : BelongsTo
- pacientes() : HasMany
- pacientesConsultorio() : HasMany (implícita)
```

### TipoDocumento
```php
- pacientes() : HasMany
- empleados() : HasMany
- pacientesConsultorio() : HasMany (implícita)
```

### AccesoVascular
```php
- paciente() : BelongsTo
- tipoAccesoVascular() : BelongsTo
- cirujano() : BelongsTo
```

### Análisis (Diario, Mensual, Trimestral, Semestral)
```php
- paciente() : BelongsTo
// Para AnalisisDiario también:
- tipoSesion() : BelongsTo
- tipoFiltro() : BelongsTo
```

### Empleado
```php
- tipoDocumento() : BelongsTo
- rol() : BelongsTo
```

### VacunaPaciente
```php
- vacuna() : BelongsTo
- paciente() : BelongsTo
- dosis() : HasMany
```

### MedicacionPaciente
```php
- medicacion() : BelongsTo
- paciente() : BelongsTo
```

## Base de Datos Completa
El sistema maneja un total de **44 tablas** que cubren:
- Gestión de pacientes (hemodiálisis y consultorio)
- Análisis médicos periódicos
- Historias clínicas
- Personal médico y empleados
- Medicaciones y vacunas
- Procedimientos médicos
- Antecedentes médicos
- Internaciones y transfusiones
- Estudios complementarios

Todos los modelos incluyen:
- Definición correcta de la tabla
- Campos fillable apropiados
- Cast de tipos de datos
- Relaciones Eloquent bidireccionales
- Manejo de fechas y campos especiales
