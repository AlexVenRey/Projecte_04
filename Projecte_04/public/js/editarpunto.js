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

    form.addEventListener("submit", async function (event) {
        event.preventDefault(); // Evitar el envío del formulario hasta que se valide
        const errors = [];
        const nombre = document.getElementById("nombre");
        const latitud = document.getElementById("latitud");
        const longitud = document.getElementById("longitud");
        const descripcion = document.getElementById("descripcion");
        const etiquetas = document.getElementById("etiquetas");
        const puntoId = form.dataset.puntoId; // ID del punto actual (pasado desde el backend)

        // Limpiar errores previos
        errorContainer.innerHTML = "";
        errorContainer.style.display = "none";
        [nombre, latitud, longitud, descripcion, etiquetas].forEach((field) => {
            field.style.borderColor = "";
        });

        // Validar campos
        if (!nombre.value.trim()) {
            errors.push("El campo 'Nombre del sitio' es obligatorio.");
            nombre.style.borderColor = "red";
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

        // Validar etiquetas
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