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
        const descripcion = document.getElementById("descripcion");
        const lugares = document.getElementById("lugares");

        // Limpiar errores previos
        errorContainer.innerHTML = "";
        errorContainer.style.display = "none";
        [nombre, descripcion, lugares].forEach((field) => {
            field.style.borderColor = "";
        });

        // Validar campos
        if (!nombre.value.trim()) {
            errors.push("El campo 'Nombre de la Gimcana' es obligatorio.");
            nombre.style.borderColor = "red";
        }

        if (!descripcion.value.trim()) {
            errors.push("El campo 'Descripción' es obligatorio.");
            descripcion.style.borderColor = "red";
        }

        if (lugares.selectedOptions.length === 0) {
            errors.push("Debe seleccionar al menos un punto de interés.");
            lugares.style.borderColor = "red";
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
