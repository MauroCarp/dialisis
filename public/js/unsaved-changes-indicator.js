/**
 * Extensión del detector de cambios sin guardar para añadir indicadores visuales
 * Proporciona feedback visual al usuario sobre el estado de los cambios
 */

class UnsavedChangesIndicator {
    constructor() {
        this.detector = window.unsavedChangesDetector;
        this.indicators = new Map();
        this.init();
    }

    init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setupIndicators());
        } else {
            this.setupIndicators();
        }
    }

    setupIndicators() {
        // Buscar formularios con indicadores
        const forms = document.querySelectorAll('form[data-track-changes]');
        forms.forEach(form => this.addIndicatorToForm(form));
    }

    addIndicatorToForm(form) {
        // Crear indicador visual
        const indicator = this.createIndicator();
        
        // Buscar el botón de submit
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            // Insertar el indicador antes del botón
            submitButton.parentNode.insertBefore(indicator, submitButton);
            this.indicators.set(form, { element: indicator, submitButton: submitButton });
        }

        // Escuchar cambios en el formulario
        this.listenToFormChanges(form);
    }

    createIndicator() {
        const indicator = document.createElement('div');
        indicator.className = 'unsaved-changes-indicator hidden';
        indicator.innerHTML = `
            <div class="flex items-center text-amber-600 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 text-sm mb-4">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span>Tienes cambios sin guardar</span>
            </div>
        `;
        return indicator;
    }

    listenToFormChanges(form) {
        const indicator = this.indicators.get(form);
        if (!indicator) return;

        // Observar cambios en los campos del formulario
        const inputs = form.querySelectorAll('input:not([type="hidden"]):not([type="submit"]), textarea, select');
        
        inputs.forEach(input => {
            input.addEventListener('input', () => this.updateIndicator(form));
            input.addEventListener('change', () => this.updateIndicator(form));
        });

        // Escuchar el submit del formulario
        form.addEventListener('submit', () => this.hideIndicator(form));
    }

    updateIndicator(form) {
        const indicator = this.indicators.get(form);
        if (!indicator) return;

        // Usar un pequeño delay para permitir que el detector de cambios se actualice
        setTimeout(() => {
            if (this.detector && this.detector.hasChanges()) {
                this.showIndicator(form);
            } else {
                this.hideIndicator(form);
            }
        }, 10);
    }

    showIndicator(form) {
        const indicator = this.indicators.get(form);
        if (indicator && indicator.element) {
            indicator.element.classList.remove('hidden');
            
            // Añadir clase de énfasis al botón de submit
            if (indicator.submitButton) {
                indicator.submitButton.classList.add('ring-2', 'ring-green-300', 'ring-opacity-50');
            }
        }
    }

    hideIndicator(form) {
        const indicator = this.indicators.get(form);
        if (indicator && indicator.element) {
            indicator.element.classList.add('hidden');
            
            // Remover clase de énfasis del botón de submit
            if (indicator.submitButton) {
                indicator.submitButton.classList.remove('ring-2', 'ring-green-300', 'ring-opacity-50');
            }
        }
    }
}

// Crear una instancia global después de que el detector base esté listo
if (window.unsavedChangesDetector) {
    window.unsavedChangesIndicator = new UnsavedChangesIndicator();
} else {
    // Si el detector no está listo, esperar a que se cargue
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            if (window.unsavedChangesDetector) {
                window.unsavedChangesIndicator = new UnsavedChangesIndicator();
            }
        }, 100);
    });
}
