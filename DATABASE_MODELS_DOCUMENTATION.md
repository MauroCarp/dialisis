# Documentación de Modelos y Relaciones - Sistema de Hemodiálisis

## Resumen de Modelos Creados

### Modelos Principales

1. **Paciente** - Información de pacientes del centro de hemodiálisis
2. **ObraSocial** - Obras sociales disponibles
3. **Localidad** - Localidades donde residen los pacientes
4. **Provincia** - Provincias de Argentina
5. **Medicacion** - Medicaciones disponibles
6. **TipoMedicacion** - Tipos de medicaciones
7. **AccesoVascular** - Accesos vasculares de los pacientes
8. **TipoAccesoVascular** - Tipos de accesos vasculares
9. **Patologia** - Patologías médicas
10. **Vacuna** - Vacunas disponibles
11. **PacienteObraSocial** - Tabla pivote para pacientes y obras sociales

## Relaciones Entre Modelos

### 1. Paciente (Modelo Central)
```php
// Relaciones del modelo Paciente:
- belongsTo: Localidad (id_localidad)
- belongsToMany: ObraSocial (a través de pacientesobrassociales)
- hasMany: AccesoVascular (id_paciente)
- belongsToMany: Patologia (a través de patologiaspacientes)
- belongsToMany: Vacuna (a través de vacunaspacientes)
```

### 2. Ubicación Geográfica
```php
// Provincia -> Localidad -> Paciente
Provincia::hasMany(Localidad::class)
Localidad::belongsTo(Provincia::class)
Localidad::hasMany(Paciente::class)
Paciente::belongsTo(Localidad::class)
```

### 3. Sistema de Medicaciones
```php
// TipoMedicacion -> Medicacion
TipoMedicacion::hasMany(Medicacion::class)
Medicacion::belongsTo(TipoMedicacion::class)
```

### 4. Sistema de Accesos Vasculares
```php
// TipoAccesoVascular -> AccesoVascular -> Paciente
TipoAccesoVascular::hasMany(AccesoVascular::class)
AccesoVascular::belongsTo(TipoAccesoVascular::class)
AccesoVascular::belongsTo(Paciente::class)
AccesoVascular::belongsTo(User::class, 'id_cirujano') // Cirujano
```

### 5. Relaciones Muchos a Muchos

#### Paciente - ObraSocial
```php
// Tabla pivote: pacientesobrassociales
- id_paciente
- id_obrasocial
- fechavigencia
- nroafiliado
```

#### Paciente - Patología
```php
// Tabla pivote: patologiaspacientes
- id_paciente
- id_patologia
```

#### Paciente - Vacuna
```php
// Tabla pivote: vacunaspacientes
- id_paciente
- id_vacuna
- fechavacunacion
```

## Campos Principales por Modelo

### Paciente
- `nroalta` - Número de alta
- `nombre`, `apellido` - Datos personales
- `pesoseco` - Peso seco del paciente
- `id_tipodocumento`, `dnicuitcuil` - Documentación
- `direccion`, `telefono`, `email` - Contacto
- `fumador`, `insulinodependiente` - Condiciones médicas
- `fechanacimiento`, `talla`, `gruposanguineo` - Datos médicos
- `id_localidad` - Ubicación
- `fechaingreso`, `fechaegreso` - Fechas de ingreso y egreso
- `id_causaingreso`, `id_causaegreso` - Causas

### ObraSocial
- `abreviatura` - Abreviatura de la obra social
- `descripcion` - Nombre completo
- `fechaBaja` - Fecha de baja (soft delete)

### AccesoVascular
- `fechaacceso` - Fecha del acceso vascular
- `observaciones` - Notas médicas
- `id_tipoacceso` - Tipo de acceso
- `id_cirujano` - Médico cirujano
- `id_paciente` - Paciente

## Uso de los Modelos

### Ejemplos de Consultas

```php
// Obtener paciente con sus obras sociales
$paciente = Paciente::with('obrasSociales')->find(1);

// Obtener accesos vasculares de un paciente
$accesos = Paciente::find(1)->accesosVasculares;

// Obtener pacientes de una localidad específica
$pacientes = Localidad::find(1)->pacientes;

// Obtener patologías de un paciente
$patologias = Paciente::find(1)->patologias;

// Obtener localidades de una provincia
$localidades = Provincia::find(1)->localidades;

// Obtener medicaciones por tipo
$medicaciones = TipoMedicacion::find(1)->medicaciones;
```

### Crear Relaciones

```php
// Asignar obra social a paciente
$paciente = Paciente::find(1);
$paciente->obrasSociales()->attach($obraSocialId, [
    'fechavigencia' => now(),
    'nroafiliado' => '12345'
]);

// Crear acceso vascular
AccesoVascular::create([
    'fechaacceso' => now(),
    'observaciones' => 'Acceso en brazo izquierdo',
    'id_tipoacceso' => 1,
    'id_cirujano' => 1,
    'id_paciente' => 1
]);
```

## Consideraciones Técnicas

1. **Soft Deletes**: Varios modelos usan `fechabaja` en lugar de soft deletes de Laravel
2. **Timestamps**: Las tablas pivote incluyen campos de timestamps automáticos
3. **Casts**: Se configuraron casts para fechas, decimales y booleanos apropiados
4. **Fillable**: Se definieron campos fillable para mass assignment seguro

## Próximos Pasos

Para completar el sistema, considera agregar:

1. **Validaciones** en los modelos usando Form Requests
2. **Scopes** para consultas comunes (ej: pacientes activos)
3. **Mutators/Accessors** para formateo de datos
4. **Resources de Filament** para administración
5. **Seeders** para datos iniciales
6. **Factories** para testing

## Estructura de Archivos Creados

```
app/Models/
├── Paciente.php ✅
├── ObraSocial.php ✅
├── Localidad.php ✅
├── Provincia.php ✅
├── Medicacion.php ✅
├── TipoMedicacion.php ✅
├── AccesoVascular.php ✅
├── TipoAccesoVascular.php ✅
├── Patologia.php ✅
├── Vacuna.php ✅
└── PacienteObraSocial.php ✅
```

Todos los modelos han sido creados con sus relaciones correspondientes y están listos para usar en tu aplicación de Filament PHP.
