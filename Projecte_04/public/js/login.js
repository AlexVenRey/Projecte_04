document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function (event) {
        // Obtener los valores de los campos
        const email = document.querySelector('input[name="email"]');
        const password = document.querySelector('input[name="password"]');
        
        // Limpiar mensajes de error previos
        clearErrors();

        let isValid = true;

        // Validar el campo de correo electrónico
        if (email.value.trim() === '') {
            showError(email, 'El correo electrónico es obligatorio');
            isValid = false;
        }

        // Validar el campo de contraseña
        if (password.value.trim() === '') {
            showError(password, 'La contraseña es obligatoria');
            isValid = false;
        }

        // Si algún campo no es válido, prevenimos el envío del formulario
        if (!isValid) {
            event.preventDefault();
        }
    });

    function showError(input, message) {
        // Crear el span para el mensaje de error
        const errorElement = document.createElement('span');
        errorElement.classList.add('error-message');
        errorElement.textContent = message;

        // Insertar el mensaje de error justo debajo del campo
        input.insertAdjacentElement('afterend', errorElement);
    }

    function clearErrors() {
        // Eliminar los mensajes de error anteriores si existen
        const errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach(error => error.remove());
    }
});
