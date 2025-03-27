// Variables globales
let mapa;
let marcadores = [];
let rutas = [];
let lugares = [];
let puntosControl = [];
let grupoActual = null;
let marcadorUsuario = null;
let circuloPerimetro = null;
let watchId = null;
let gimcanaId = null;
let puntoControlActual = null;
let radioProximidad = 20; // Radio en metros
let siguiendoUsuario = true;

// Inicializar el mapa cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    gimcanaId = document.querySelector('meta[name="gimcana-id"]').content;
    if (!gimcanaId) {
        mostrarError('No se ha encontrado el ID de la gimcana');
        return;
    }

    // Inicializar el mapa
    mapa = L.map('mapa').setView([41.3851, 2.1734], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mapa);

    cargarPuntosControl();
    iniciarSeguimientoUbicacion();
    setInterval(actualizarPosicionesUsuarios, 5000);
});

function iniciarSeguimientoUbicacion() {
    if (!("geolocation" in navigator)) {
        mostrarError("Tu navegador no soporta geolocalización");
        return;
    }

    // Icono simple y profesional para el usuario
    const iconoUsuario = L.divIcon({
        className: 'usuario-marker',
        html: '<div class="marcador-usuario"></div>',
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });

    const opcionesGeo = {
        enableHighAccuracy: true,
        maximumAge: 0,
        timeout: 5000
    };

    function actualizarPosicion(position) {
        const { latitude, longitude, accuracy } = position.coords;

        if (!marcadorUsuario) {
            // Crear marcador del usuario
            marcadorUsuario = L.marker([latitude, longitude], {
                icon: iconoUsuario
            }).addTo(mapa);

            // Crear círculo de perímetro
            circuloPerimetro = L.circle([latitude, longitude], {
                radius: radioProximidad,
                color: '#2196F3',
                fillColor: '#2196F3',
                fillOpacity: 0.1,
                weight: 2
            }).addTo(mapa);

            mapa.setView([latitude, longitude], 18);
        } else {
            // Actualizar posición del marcador y círculo
            marcadorUsuario.setLatLng([latitude, longitude]);
            circuloPerimetro.setLatLng([latitude, longitude]);
        }

        // Si el seguimiento está activo, centrar el mapa en el usuario
        if (siguiendoUsuario) {
            mapa.setView([latitude, longitude]);
        }

        verificarProximidadPuntosControl(latitude, longitude);
        enviarPosicionAlServidor(latitude, longitude);
    }

    function manejarError(error) {
        let mensaje = "Error de ubicación: ";
        switch(error.code) {
            case error.PERMISSION_DENIED:
                mensaje += "Permiso denegado";
                break;
            case error.POSITION_UNAVAILABLE:
                mensaje += "Ubicación no disponible";
                break;
            case error.TIMEOUT:
                mensaje += "Tiempo de espera agotado";
                break;
            default:
                mensaje += "Error desconocido";
        }
        mostrarError(mensaje);
    }

    // Iniciar seguimiento continuo
    watchId = navigator.geolocation.watchPosition(
        actualizarPosicion,
        manejarError,
        opcionesGeo
    );

    // Añadir botón para centrar en el usuario
    const botonCentrar = L.control({position: 'bottomright'});
    botonCentrar.onAdd = function() {
        const div = L.DomUtil.create('div', 'boton-centrar');
        div.innerHTML = '<button class="btn-centrar" title="Centrar en mi ubicación"><i class="fas fa-crosshairs"></i></button>';
        div.onclick = function() {
            siguiendoUsuario = true;
            if (marcadorUsuario) {
                const pos = marcadorUsuario.getLatLng();
                mapa.setView(pos, 18);
            }
        };
        return div;
    };
    botonCentrar.addTo(mapa);

    // Desactivar seguimiento cuando el usuario mueve el mapa
    mapa.on('dragstart', function() {
        siguiendoUsuario = false;
    });
}

function verificarProximidadPuntosControl(latitud, longitud) {
    if (!puntoControlActual) return;

    const distancia = calcularDistancia(
        latitud, 
        longitud, 
        puntoControlActual.latitud, 
        puntoControlActual.longitud
    );

    if (distancia <= radioProximidad) {
        mostrarPistaYPrueba(puntoControlActual);
    }
}

function cargarPuntosControl() {
    fetch(`/cliente/gimcanas/${gimcanaId}/siguiente-punto`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                puntoControlActual = data;
                mostrarPuntosControlEnMapa();
            }
        })
        .catch(error => console.error('Error al cargar punto de control:', error));
}

function mostrarPuntosControlEnMapa() {
    limpiarMarcadores();

    if (puntoControlActual) {
        const marcador = L.marker([puntoControlActual.latitud, puntoControlActual.longitud], {
            icon: L.divIcon({
                className: 'punto-control-marker',
                html: `<div class="marcador-punto">
                        <span class="numero">!</span>
                       </div>`,
                iconSize: [30, 30],
                iconAnchor: [15, 30]
            })
        }).addTo(mapa);
        marcadores.push(marcador);
    }
}

// Estilos más simples y profesionales
const style = document.createElement('style');
style.textContent = `
    .marcador-usuario {
        width: 20px;
        height: 20px;
        background-color: #2196F3;
        border: 2px solid white;
        border-radius: 50%;
        box-shadow: 0 0 4px rgba(0,0,0,0.3);
    }

    .marcador-punto {
        width: 30px;
        height: 30px;
        background-color: #FF5722;
        border: 2px solid white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        box-shadow: 0 0 4px rgba(0,0,0,0.3);
    }

    .btn-centrar {
        width: 40px;
        height: 40px;
        background-color: white;
        border: 2px solid rgba(0,0,0,0.2);
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #666;
        transition: all 0.3s ease;
    }

    .btn-centrar:hover {
        background-color: #f4f4f4;
        color: #2196F3;
    }
`;
document.head.appendChild(style);

// Función para cargar información del grupo
function cargarInformacionGrupo() {
    fetch(`/cliente/gimcanas/${gimcanaId}/grupo-actual`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                grupoActual = data.grupo;
                actualizarPanelGrupo();
            }
        })
        .catch(error => console.error('Error al cargar grupo:', error));
}

// Función para mostrar pista y prueba
function mostrarPistaYPrueba(puntoControl) {
    Swal.fire({
        title: '¡Has llegado a un punto de control!',
        html: `
            <h4>Pista:</h4>
            <p>${puntoControl.pista}</p>
            <h4>Prueba:</h4>
            <p>${puntoControl.prueba.descripcion}</p>
        `,
        showCancelButton: true,
        confirmButtonText: 'Resolver prueba',
        cancelButtonText: 'Cerrar'
    }).then((result) => {
        if (result.isConfirmed) {
            mostrarFormularioPrueba(puntoControl);
        }
    });
}

// Función para mostrar el formulario de la prueba
function mostrarFormularioPrueba(puntoControl) {
    Swal.fire({
        title: 'Resolver prueba',
        html: `
            <form id="formPrueba">
                <div class="form-group">
                    <label>Tu respuesta:</label>
                    <input type="text" class="form-control" id="respuestaPrueba" required>
                </div>
            </form>
        `,
        confirmButtonText: 'Enviar respuesta',
        showCancelButton: true,
        preConfirm: () => {
            return document.getElementById('respuestaPrueba').value;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            verificarRespuestaPrueba(puntoControl.id, result.value);
        }
    });
}

// Función para verificar la respuesta de la prueba
function verificarRespuestaPrueba(puntoControlId, respuesta) {
    fetch('/cliente/gimcanas/verificar-prueba', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            punto_control_id: puntoControlId,
            respuesta: respuesta,
            gimcana_id: gimcanaId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Correcto!',
                text: 'Has superado esta prueba'
            });
            puntoControlActual++;
            actualizarProgresoGimcana();
            if (data.gimcana_completada) {
                mostrarGimcanaCompletada();
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Incorrecto',
                text: 'Inténtalo de nuevo'
            });
        }
    });
}

// Función para mostrar los puntos de control en el mapa
function mostrarPuntosControlEnMapa() {
    limpiarMarcadores();

    puntosControl.forEach((punto, index) => {
        const esSiguientePunto = index === puntoControlActual;
        const marcador = L.marker([punto.lugar.latitud, punto.lugar.longitud], {
            icon: L.divIcon({
                className: `marcador-gimcana ${esSiguientePunto ? 'lugar-siguiente' : ''}`,
                html: `<div class="marcador" style="background-color: ${esSiguientePunto ? '#00ff00' : '#ff4444'}">
                        <span class="numero">${index + 1}</span>
                       </div>`,
                iconSize: [40, 40],
                iconAnchor: [20, 40]
            })
        });

        marcador.addTo(mapa);
        marcadores.push(marcador);
    });
}

// Función para actualizar el panel del grupo
function actualizarPanelGrupo() {
    const nombreGrupoSpan = document.getElementById('nombre-grupo');
    const miembrosGrupoDiv = document.getElementById('miembros-grupo');

    if (grupoActual) {
        nombreGrupoSpan.textContent = grupoActual.nombre;
        miembrosGrupoDiv.innerHTML = grupoActual.usuarios.map(usuario => `
            <span class="badge bg-secondary me-1">
                ${usuario.nombre}
                ${usuario.pivot.esta_listo ? '<i class="fas fa-check-circle text-success"></i>' : ''}
            </span>
        `).join('');
    }
}

// Función para mostrar gimcana completada
function mostrarGimcanaCompletada() {
    Swal.fire({
        title: '¡Felicidades!',
        text: 'Has completado la gimcana con tu grupo',
        icon: 'success',
        confirmButtonText: 'Volver al inicio'
    }).then(() => {
        window.location.href = '/cliente/gimcanas';
    });
}

// Función para enviar la posición al servidor
function enviarPosicionAlServidor(latitude, longitude) {
    if (!gimcanaId) return;

    fetch('/cliente/actualizar-posicion', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            latitud: latitude,
            longitud: longitude,
            gimcana_id: gimcanaId,
            timestamp: new Date().toISOString()
        })
    }).catch(error => console.error('Error al actualizar posición:', error));
}

// Función para detener el seguimiento de ubicación
function detenerSeguimientoUbicacion() {
    if (watchId !== null) {
        navigator.geolocation.clearWatch(watchId);
        watchId = null;
    }
    if (marcadorUsuario && marcadorUsuario.circle) {
        mapa.removeLayer(marcadorUsuario.circle);
    }
    if (marcadorUsuario) {
        mapa.removeLayer(marcadorUsuario);
        marcadorUsuario = null;
    }
}

// Asegurarse de detener el seguimiento cuando se cierre la página
window.addEventListener('beforeunload', detenerSeguimientoUbicacion);

// Función para calcular la distancia entre dos puntos
function calcularDistancia(lat1, lon1, lat2, lon2) {
    const R = 6371e3; // Radio de la tierra en metros
    const φ1 = lat1 * Math.PI/180;
    const φ2 = lat2 * Math.PI/180;
    const Δφ = (lat2-lat1) * Math.PI/180;
    const Δλ = (lon2-lon1) * Math.PI/180;

    const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
            Math.cos(φ1) * Math.cos(φ2) *
            Math.sin(Δλ/2) * Math.sin(Δλ/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

    return R * c;
}

// Función para finalizar la gimcana
function finalizarGimcana() {
    Swal.fire({
        title: '¡Felicidades!',
        text: 'Has completado la gimcana',
        icon: 'success'
    }).then(() => {
        window.location.href = '/cliente/gimcanas';
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

// Función para actualizar el progreso de la gimcana
function actualizarProgresoGimcana() {
    if (!gimcanaId) return;

    fetch(`/cliente/gimcanas/${gimcanaId}/progreso`)
        .then(response => {
            if (!response.ok) throw new Error('Error al obtener progreso');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const lugaresCompletados = document.getElementById('lugares-completados');
                const totalLugares = document.getElementById('total-lugares');
                if (lugaresCompletados && totalLugares) {
                    lugaresCompletados.textContent = data.completados;
                    totalLugares.textContent = data.total;
                    lugarActual = data.completados;
                }
            }
        })
        .catch(error => console.error('Error al actualizar progreso:', error));
}
