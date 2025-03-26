document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Evitar el envío del formulario hasta que se valide
        let isValid = true;

        // Obtener los campos del formulario
        const nombre = document.getElementById("nombre");
        const email = document.getElementById("email");
        const password = document.getElementById("password");
        const passwordConfirmation = document.getElementById("password_confirmation");
        const rol = document.getElementById("rol");

        // Limpiar mensajes de error previos
        document.querySelectorAll(".error-message").forEach(el => el.remove());
        [nombre, email, password, passwordConfirmation, rol].forEach(field => field.style.borderColor = "");

        // Validar campo "Nombre"
        if (!nombre.value.trim()) {
            mostrarError(nombre, "El campo 'Nombre' es obligatorio.");
            isValid = false;
        }

        // Validar campo "Email"
        if (!email.value.trim()) {
            mostrarError(email, "El campo 'Email' es obligatorio.");
            isValid = false;
        } else if (!validarEmail(email.value)) {
            mostrarError(email, "El formato del email no es válido.");
            isValid = false;
        }

        // Validar campo "Contraseña"
        if (!password.value.trim()) {
            mostrarError(password, "El campo 'Contraseña' es obligatorio.");
            isValid = false;
        } else if (password.value.length < 8) {
            mostrarError(password, "La contraseña debe tener al menos 8 caracteres.");
            isValid = false;
        }

        // Validar campo "Confirmar Contraseña"
        if (!passwordConfirmation.value.trim()) {
            mostrarError(passwordConfirmation, "El campo 'Confirmar Contraseña' es obligatorio.");
            isValid = false;
        } else if (password.value !== passwordConfirmation.value) {
            mostrarError(passwordConfirmation, "Las contraseñas no coinciden.");
            isValid = false;
        }

        // Validar campo "Rol"
        if (!rol.value.trim()) {
            mostrarError(rol, "El campo 'Rol' es obligatorio.");
            isValid = false;
        }

        // Si no hay errores, enviar el formulario
        if (isValid) {
            form.submit();
        }
    });

    // Función para mostrar mensajes de error debajo del campo correspondiente
    function mostrarError(elemento, mensaje) {
        const error = document.createElement("p");
        error.className = "error-message";
        error.style.color = "red";
        error.style.fontSize = "14px";
        error.style.marginTop = "5px";
        error.textContent = mensaje;
        elemento.style.borderColor = "red";
        elemento.parentElement.appendChild(error);
    }

    // Función para validar el formato del email
    function validarEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
});