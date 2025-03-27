document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Evitar el envío del formulario hasta que se valide
        let isValid = true;

        // Obtener los campos del formulario
        const nombre = document.getElementById("nombre");
        const descripcion = document.getElementById("descripcion");
        const lugares = document.querySelectorAll('input[name="lugares[]"]:checked');

        // Limpiar mensajes de error previos
        document.querySelectorAll(".error-message").forEach(el => el.remove());
        [nombre, descripcion].forEach(field => field.style.borderColor = "");

        // Validar campo "Nombre"
        if (!nombre.value.trim()) {
            mostrarError(nombre, "Este campo es obligatorio.");
            isValid = false;
        }

        // Validar campo "Descripción"
        if (!descripcion.value.trim()) {
            mostrarError(descripcion, "Este campo es obligatorio.");
            isValid = false;
        }

        // Validar selección de "Lugares"
        if (lugares.length === 0) {
            // Obtenemos el contenedor donde se encuentran los lugares
            const lugaresContainer = document.querySelector('.etiquetas-grid');
            mostrarError(lugaresContainer, "Debes seleccionar al menos un punto de interés.");
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
        error.style.marginBottom = "10px"; // Un pequeño margen después del mensaje de error
        error.textContent = mensaje;
        elemento.style.borderColor = "red"; // Cambiar borde si es necesario

        // Agregar el mensaje de error al contenedor del elemento
        if (elemento && elemento.parentElement) {
            elemento.parentElement.appendChild(error);
        }
    }
});
