/**
 * Detector de cambios sin guardar para formularios
 * Detecta cambios en formularios y alerta al usuario antes de salir sin guardar
 */

class UnsavedChangesDetector {
    constructor() {
        this.hasUnsavedChanges = false;
        this.isSubmitting = false;
        this.trackedForms = new Map();
        this.init();
    }

    init() {
        // Escuchar cuando el DOM esté listo
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setupDetection());
        } else {
            this.setupDetection();
        }

        // Detectar intentos de salir de la página
        window.addEventListener('beforeunload', (e) => this.handleBeforeUnload(e));

        // Interceptar clics en enlaces
        document.addEventListener('click', (e) => this.handleLinkClick(e));
    }

    setupDetection() {
        // Buscar todos los formularios que deberían ser monitoreados
        const forms = document.querySelectorAll('form[data-track-changes], form:has(textarea[name="observaciones"]), form:has(input[name="fechahistoriaclinica"])');
        
        forms.forEach(form => this.trackForm(form));
    }

    trackForm(form) {
        if (this.trackedForms.has(form)) {
            return; // Ya está siendo rastreado
        }

        const inputs = form.querySelectorAll('input:not([type="hidden"]):not([type="submit"]), textarea, select');
        
        // Guardar valores iniciales
        const initialValues = {};
        inputs.forEach(input => {
            initialValues[input.name || input.id] = this.getInputValue(input);
        });

        this.trackedForms.set(form, { initialValues, inputs });

        // Detectar cambios en los campos
        inputs.forEach(input => {
            input.addEventListener('input', () => this.checkForChanges(form));
            input.addEventListener('change', () => this.checkForChanges(form));
        });

        // Marcar como enviando cuando se submit el formulario
        form.addEventListener('submit', () => this.markAsSubmitting());
    }

    getInputValue(input) {
        if (input.type === 'checkbox' || input.type === 'radio') {
            return input.checked;
        }
        return input.value;
    }

    checkForChanges(form) {
        const formData = this.trackedForms.get(form);
        if (!formData) return;

        const { initialValues, inputs } = formData;
        let hasChanges = false;

        inputs.forEach(input => {
            const key = input.name || input.id;
            const currentValue = this.getInputValue(input);
            const initialValue = initialValues[key];

            if (currentValue !== initialValue) {
                hasChanges = true;
            }
        });

        this.hasUnsavedChanges = hasChanges;
    }

    markAsSubmitting() {
        this.isSubmitting = true;
        this.hasUnsavedChanges = false;
    }

    handleBeforeUnload(e) {
        if (this.hasUnsavedChanges && !this.isSubmitting) {
            const message = 'La HISTORIA CLINICA no está guardada. ¿Estás seguro de que quieres salir sin guardar?';
            e.preventDefault();
            e.returnValue = message;
            return message;
        }
    }

    handleLinkClick(e) {
        const target = e.target.closest('a');
        if (target && this.hasUnsavedChanges && !this.isSubmitting) {
            const href = target.getAttribute('href');
            if (href && href !== '#' && !href.startsWith('javascript:') && !href.startsWith('mailto:') && !href.startsWith('tel:')) {
                e.preventDefault();
                if (confirm('La HISTORIA CLINICA no está guardada. ¿Estás seguro de que quieres salir sin guardar?')) {
                    this.isSubmitting = true;
                    window.location.href = href;
                }
            }
        }
    }

    // Método público para resetear el estado
    reset() {
        this.hasUnsavedChanges = false;
        this.isSubmitting = false;
    }

    // Método público para marcar cambios manualmente
    markChanged() {
        this.hasUnsavedChanges = true;
    }

    // Método público para verificar si hay cambios
    hasChanges() {
        return this.hasUnsavedChanges;
    }
}

// Crear una instancia global
window.unsavedChangesDetector = new UnsavedChangesDetector();

// También exportar para uso en módulos si es necesario
if (typeof module !== 'undefined' && module.exports) {
    module.exports = UnsavedChangesDetector;
}
