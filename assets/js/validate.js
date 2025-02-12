// Validálatlan űrlapok küldésének megakadályozása
(() => {
    'use strict'
    // Minden validálandó űrlap meghatározása
    const forms = document.querySelectorAll('.needs-validation')

    // Ellenőriz -> megakadályoz
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }

            form.classList.add('was-validated')
        }, false)
    })
})();

