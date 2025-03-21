document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function (event) {
        // Obtener los valores de los campos
        const name = document.querySelector('input[name="name"]');
        const email = document.querySelector('input[name="email"]');
        const password = document.querySelector('input[name="password"]');
        const passwordConfirmation = document.querySelector('input[name="password_confirmation"]');
        
        // Limpiar mensajes de error previos
        clearErrors();

        let isValid = true;

        // Validar el campo de nombre
        if (name.value.trim() === '') {
            showError(name, 'El nombre completo es obligatorio');
            isValid = false;
        }

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

        // Validar el campo de confirmación de contraseña
        if (passwordConfirmation.value.trim() === '') {
            showError(passwordConfirmation, 'La confirmación de la contraseña es obligatoria');
            isValid = false;
        } else if (password.value !== passwordConfirmation.value) {
            showError(passwordConfirmation, 'Las contraseñas no coinciden');
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
