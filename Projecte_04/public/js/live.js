// Variables globales
let mapa;
let marcadores = [];
let rutas = [];
let lugares = [];
let grupoActual = null;

// Inicializar el mapa cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Obtener el ID de la gimcana de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const gimcanaId = urlParams.get('gimcana_id');

    // Inicializar el mapa
    mapa = L.map('mapa').setView([41.3851, 2.1734], 13); // Barcelona como centro inicial

    // Añadir capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mapa);

    // Cargar los lugares de la gimcana
    cargarLugaresGimcana(gimcanaId);
});

// Función para cargar los lugares de la gimcana
function cargarLugaresGimcana(gimcanaId) {
    fetch(`/cliente/gimcanas/${gimcanaId}/lugares`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                lugares = data.lugares;
                mostrarLugaresEnMapa();
            } else {
                mostrarError('Error al cargar los lugares');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarError('Error al cargar los lugares');
        });
}

// Función para mostrar los lugares en el mapa
function mostrarLugaresEnMapa() {
    // Limpiar marcadores existentes
    limpiarMarcadores();

    lugares.forEach((lugar, index) => {
        const marcador = L.marker([lugar.latitud, lugar.longitud], {
            icon: L.divIcon({
                className: 'marcador-gimcana',
                html: `<div class="marcador" style="background-color: ${lugar.color_marcador}">
                        <span class="numero">${index + 1}</span>
                       </div>`,
                iconSize: [40, 40],
                iconAnchor: [20, 40]
            })
        });

        marcador.addTo(mapa);
        marcador.on('click', () => mostrarDetallesLugar(lugar));
        marcadores.push(marcador);
    });
}

// Función para mostrar los detalles de un lugar
function mostrarDetallesLugar(lugar) {
    const modal = document.getElementById('detallesModal');
    const modalTitle = modal.querySelector('.modal-title');
    const modalBody = modal.querySelector('.modal-body');
    const btnRuta = document.getElementById('btnRuta');

    modalTitle.textContent = lugar.nombre;
    modalBody.innerHTML = `
        <p><strong>Descripción:</strong> ${lugar.descripcion}</p>
        <p><strong>Etiquetas:</strong> ${lugar.etiquetas.map(e => e.nombre).join(', ')}</p>
    `;

    // Configurar el botón de ruta
    btnRuta.onclick = () => mostrarRuta(lugar);

    // Mostrar el modal
    new bootstrap.Modal(modal).show();
}

// Función para mostrar la ruta a un lugar
function mostrarRuta(lugar) {
    // Limpiar rutas existentes
    limpiarRutas();

    // Obtener la ubicación actual del usuario
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            position => {
                const origen = [position.coords.latitude, position.coords.longitude];
                const destino = [lugar.latitud, lugar.longitud];

                // Crear la ruta
                const ruta = L.Routing.control({
                    waypoints: [
                        L.latLng(origen[0], origen[1]),
                        L.latLng(destino[0], destino[1])
                    ],
                    routeWhileDragging: false,
                    showAlternatives: false,
                    fitSelectedRoutes: true,
                    lineOptions: {
                        styles: [{ color: '#6FA1E2', weight: 4 }]
                    }
                }).addTo(mapa);

                rutas.push(ruta);
            },
            error => {
                mostrarError('Error al obtener tu ubicación');
            }
        );
    } else {
        mostrarError('Tu navegador no soporta la geolocalización');
    }
}

// Función para limpiar los marcadores del mapa
function limpiarMarcadores() {
    marcadores.forEach(marcador => mapa.removeLayer(marcador));
    marcadores = [];
}

// Función para limpiar las rutas del mapa
function limpiarRutas() {
    rutas.forEach(ruta => mapa.removeLayer(ruta));
    rutas = [];
}

// Función para mostrar errores
function mostrarError(mensaje) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: mensaje
    });
}

// Función para mostrar mensajes de éxito
function mostrarExito(mensaje) {
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: mensaje
    });
}

// Función para verificar si todos los usuarios están listos
function verificarTodosListos(gimcanaId) {
    fetch(`/cliente/gimcanas/${gimcanaId}/verificar-todos-listos`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.todos_listos) {
                // Solo mostrar el SweetAlert si todos están listos
                Swal.fire({
                    icon: 'success',
                    title: '¡Todos los usuarios están listos!',
                    text: data.message
                }).then(() => {
                    // Redirigir a la vista live.blade.php con la gimcana
                    window.location.href = `/cliente/gimcanas/${gimcanaId}/live`;
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarError('Error al verificar el estado de los usuarios');
        });
}

// Llamar a la función verificarTodosListos cada cierto tiempo
setInterval(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const gimcanaId = urlParams.get('gimcana_id');
    verificarTodosListos(gimcanaId);
}, 5000); // Verificar cada 5 segundos
