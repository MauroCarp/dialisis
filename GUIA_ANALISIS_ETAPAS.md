# Guía de Uso: Análisis Diarios en Dos Etapas

## Descripción General

El sistema de análisis diarios ahora permite cargar los datos en dos momentos diferentes del día:

1. **Pre-Diálisis**: Se cargan los datos antes de comenzar la sesión de diálisis
2. **Post-Diálisis**: Se completan los datos después de finalizar la sesión

## Flujo de Trabajo

### Paso 1: Carga Pre-Diálisis (Primera parte del día)

En la sección **"Datos Pre-Diálisis"** (fondo azul), el personal médico debe cargar:

- **Fecha**: Fecha del análisis (por defecto la fecha actual)
- **Peso Pre (kg)**: Peso del paciente antes de la diálisis
- **TAS Pre**: Tensión arterial sistólica antes de la diálisis
- **TAD Pre**: Tensión arterial diastólica antes de la diálisis
- **Tipo de Filtro**: Seleccionar el tipo de filtro a utilizar
- **Rel. Peso Seco/Pre**: Relación entre peso seco y peso pre
- **Interdiálitico**: Tiempo entre sesiones

Al guardar, el análisis queda en estado **"pre_dialisis"** y aparece en la sección de "Análisis Pendientes de Completar".

### Paso 2: Completar Post-Diálisis (Segunda parte del día)

Una vez finalizada la sesión de diálisis:

1. En la sección **"Análisis Pendientes de Completar"** (fondo amarillo), localizar el análisis del día
2. Hacer clic en el botón **"Completar"** 
3. Se desplegará el formulario **"Completar Análisis - Datos Post-Diálisis"** (fondo verde)

En este formulario completar:

- **Peso Post (kg)**: Peso del paciente después de la diálisis
- **TAS Post**: Tensión arterial sistólica después de la diálisis
- **TAD Post**: Tensión arterial diastólica después de la diálisis
- **Tipo de Sesión**: Tipo de sesión realizada (opcional)
- **Observaciones**: Cualquier observación sobre la sesión

Al guardar, el análisis pasa a estado **"completo"** y aparece en la lista de "Análisis Diarios Completos".

## Estados del Análisis

- **pre_dialisis**: Solo se han cargado los datos pre-diálisis
- **post_dialisis**: Solo se han cargado los datos post-diálisis (caso poco común)
- **completo**: Se han cargado tanto los datos pre como post diálisis

## Características Especiales

### Validaciones
- No se pueden completar datos post-diálisis si no existen datos pre-diálisis para esa fecha
- Todos los campos obligatorios deben completarse

### Actualización de Datos
- Si se vuelve a cargar datos pre-diálisis para una fecha existente, se actualizan los datos sin perder los post-diálisis (si existen)

### Visualización
- Los análisis pendientes se muestran destacados en amarillo
- Los análisis completos se muestran en la lista desplegable con badge verde
- Los análisis completos muestran todos los datos en una vista consolidada

## Ventajas del Sistema en Dos Etapas

1. **Flexibilidad**: Permite cargar datos en momentos apropiados del día
2. **Control**: Evita pérdida de datos si la sesión se interrumpe
3. **Seguimiento**: Fácil identificación de sesiones pendientes de completar
4. **Validación**: Garantiza que no se pierdan datos importantes
5. **Flujo Natural**: Se adapta al flujo de trabajo real del centro de diálisis

## Migración de Datos Existentes

Los análisis existentes se consideran automáticamente como "completos" para mantener la compatibilidad.

## Soporte Técnico

Para cualquier consulta sobre el uso del sistema, contactar al administrador del sistema.

---

**Fecha de Implementación**: {{ now()->format('d/m/Y') }}
**Versión**: 2.0 - Análisis en Dos Etapas
