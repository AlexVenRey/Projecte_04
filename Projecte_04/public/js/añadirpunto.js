document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const errorContainer = document.createElement("div");
    errorContainer.style.backgroundColor = "#f8d7da";
    errorContainer.style.color = "#721c24";
    errorContainer.style.padding = "10px";
    errorContainer.style.marginBottom = "15px";
    errorContainer.style.border = "1px solid #f5c6cb";
    errorContainer.style.borderRadius = "5px";
    errorContainer.style.display = "none";
    form.prepend(errorContainer);

    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Evitar el envío del formulario hasta que se valide
        const errors = [];
        const nombre = document.getElementById("nombre");
        const latitud = document.getElementById("latitud");
        const longitud = document.getElementById("longitud");
        const descripcion = document.getElementById("descripcion");
        const icono = document.getElementById("icono");
        const etiquetas = document.getElementById("etiquetas");

        // Limpiar errores previos
        errorContainer.innerHTML = "";
        errorContainer.style.display = "none";
        [nombre, latitud, longitud, descripcion, icono, etiquetas].forEach((field) => {
            field.style.borderColor = "";
        });

        // Validar campos
        if (!nombre.value.trim()) {
            errors.push("El campo 'Nombre del sitio' es obligatorio.");
            nombre.style.borderColor = "red";
        } else {
            // Verificar si el nombre ya existe mediante AJAX
            const xhr = new XMLHttpRequest();
            xhr.open("GET", `/admin/puntos/check-nombre?nombre=${encodeURIComponent(nombre.value.trim())}`, false);
            xhr.send();
            if (xhr.status === 200 && JSON.parse(xhr.responseText).exists) {
                errors.push("El nombre del sitio ya existe.");
                nombre.style.borderColor = "red";
            }
        }

        if (!latitud.value.trim() || isNaN(latitud.value)) {
            errors.push("El campo 'Latitud' es obligatorio y debe ser un número.");
            latitud.style.borderColor = "red";
        }

        if (!longitud.value.trim() || isNaN(longitud.value)) {
            errors.push("El campo 'Longitud' es obligatorio y debe ser un número.");
            longitud.style.borderColor = "red";
        }

        if (!descripcion.value.trim()) {
            errors.push("El campo 'Pista' es obligatorio.");
            descripcion.style.borderColor = "red";
        }

        if (!icono.value.trim()) {
            errors.push("El campo 'Icono' es obligatorio.");
            icono.style.borderColor = "red";
        }

        if (!etiquetas.selectedOptions.length) {
            errors.push("Debe seleccionar al menos una etiqueta.");
            etiquetas.style.borderColor = "red";
        }

        // Mostrar errores si los hay
        if (errors.length > 0) {
            errorContainer.innerHTML = errors.join("<br>");
            errorContainer.style.display = "block";
            return;
        }


        // Si no hay errores, enviar el formulario
        form.submit();
    });
});