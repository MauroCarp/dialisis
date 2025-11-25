# Detector de Cambios Sin Guardar para Historias Clínicas

## Descripción

Esta funcionalidad detecta automáticamente cuando un usuario ha hecho cambios en formularios de historias clínicas sin guardarlos, y muestra alertas apropiadas para evitar la pérdida de datos.

## Archivos Involucrados

### JavaScript
- `/public/js/unsaved-changes-detector.js` - Script principal de detección
- `/public/js/unsaved-changes-indicator.js` - Indicadores visuales adicionales

### Vistas Actualizadas
- `/resources/views/historias-clinicas/create.blade.php`
- `/resources/views/historias-clinicas-consultorio/create.blade.php`
- `/resources/views/pacientes/show.blade.php`
- `/resources/views/pacientes/show_new.blade.php`
- `/resources/views/pacientes/partials/historias-clinicas.blade.php`

## Funcionalidades

### 1. Detección Automática de Cambios
- Monitorea automáticamente todos los campos de formularios relacionados con historias clínicas
- Detecta cambios en campos de texto, textareas, selects, checkboxes y radio buttons
- Compara valores actuales con valores iniciales

### 2. Alertas de Navegación
- **beforeunload**: Muestra alerta del navegador cuando se intenta cerrar la ventana/pestaña
- **Interceptación de enlaces**: Detecta clics en enlaces y muestra confirmación personalizada
- **Prevención de pérdida de datos**: Permite al usuario cancelar la navegación

### 3. Indicadores Visuales
- Muestra un banner de advertencia cuando hay cambios sin guardar
- Resalta el botón de "Guardar" cuando hay cambios pendientes
- Se oculta automáticamente al enviar el formulario

## Cómo Funciona

### Detección de Formularios
El sistema detecta automáticamente formularios que deben ser monitoreados mediante:
1. Atributo `data-track-changes` en el formulario
2. Presencia de campos específicos como `textarea[name="observaciones"]`
3. Presencia de campos como `input[name="fechahistoriaclinica"]`

### Eventos Monitoreados
- `input` - Para cambios en tiempo real
- `change` - Para cambios de foco
- `submit` - Para marcar el formulario como enviado
- `beforeunload` - Para detectar intentos de salir
- `click` en enlaces - Para interceptar navegación

### Estados del Sistema
- `hasUnsavedChanges`: Boolean que indica si hay cambios sin guardar
- `isSubmitting`: Boolean que indica si el formulario se está enviando
- `trackedForms`: Map que almacena información de formularios monitoreados

## Mensajes de Usuario

### Alerta de Navegación
```
"Tienes cambios sin guardar. ¿Estás seguro de que quieres salir sin guardar?"
```

### Indicador Visual
```
⚠️ Tienes cambios sin guardar
```

## Configuración

### Para Nuevos Formularios
Para que un formulario sea monitoreado automáticamente, asegúrate de:

1. **Agregar el atributo data-track-changes:**
```html
<form method="POST" action="..." data-track-changes>
```

2. **Incluir los scripts necesarios:**
```html
<script src="{{ asset('js/unsaved-changes-detector.js') }}"></script>
<script src="{{ asset('js/unsaved-changes-indicator.js') }}"></script>
```

### Personalización

#### Excluir Enlaces Específicos
Los siguientes tipos de enlaces son ignorados automáticamente:
- `href="#"`
- `href="javascript:..."`
- `href="mailto:..."`
- `href="tel:..."`

#### Campos Ignorados
Los siguientes tipos de campos son ignorados:
- `input[type="hidden"]`
- `input[type="submit"]`

## API Pública

### Métodos Disponibles
```javascript
// Acceso a la instancia global
const detector = window.unsavedChangesDetector;

// Verificar si hay cambios
detector.hasChanges(); // returns boolean

// Marcar cambios manualmente
detector.markChanged();

// Resetear el estado
detector.reset();

// Marcar como enviando
detector.markAsSubmitting();
```

## Compatibilidad

- ✅ Chrome/Edge (moderno)
- ✅ Firefox
- ✅ Safari
- ✅ Dispositivos móviles

## Notas Técnicas

### Prevención de Falsos Positivos
- Compara valores actuales con iniciales, no solo detecta eventos
- Ignora campos ocultos y de submit
- Maneja correctamente checkboxes y radio buttons

### Rendimiento
- Uso mínimo de memoria mediante WeakMap cuando está disponible
- Event listeners eficientes con delegación de eventos
- Limpieza automática de referencias

### Accesibilidad
- Mensajes claros y comprensibles
- No interfiere con la navegación por teclado
- Compatible con lectores de pantalla
