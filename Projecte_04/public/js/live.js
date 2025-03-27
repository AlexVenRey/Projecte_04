// Variables globales
let mapa;
let marcadores = [];
let rutas = [];
let lugares = [];
let puntosControl = [];
let grupoActual = null;
let marcadorUsuario = null;
let watchId = null;
let gimcanaId = null;
let puntoControlActual = 0;
let radioProximidad = 20; // Radio en metros para detectar llegada a un punto

// Inicializar el mapa cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Obtener el ID de la gimcana del meta tag
    gimcanaId = document.querySelector('meta[name="gimcana-id"]').content;

    if (!gimcanaId) {
        mostrarError('No se ha encontrado el ID de la gimcana');
        return;
    }

    // Inicializar el mapa
    mapa = L.map('mapa').setView([41.3851, 2.1734], 13);

    // Añadir capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mapa);

    // Cargar información del grupo y puntos de control
    cargarInformacionGrupo();
    cargarPuntosControl();

    // Iniciar seguimiento de ubicación
    iniciarSeguimientoUbicacion();

    // Iniciar actualizaciones periódicas
    setInterval(actualizarPosicionesUsuarios, 5000);
    setInterval(actualizarProgresoGimcana, 5000);
});

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

// Función para cargar los puntos de control
function cargarPuntosControl() {
    fetch(`/cliente/gimcanas/${gimcanaId}/puntos-control`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                puntosControl = data.puntos_control;
                mostrarPuntosControlEnMapa();
            }
        })
        .catch(error => console.error('Error al cargar puntos de control:', error));
}

// Función para verificar proximidad a puntos de control
function verificarProximidadPuntosControl(latitud, longitud) {
    if (!puntosControl.length || !grupoActual) return;

    const puntoActual = puntosControl[puntoControlActual];
    if (!puntoActual) return;

    const distancia = calcularDistancia(latitud, longitud, puntoActual.lugar.latitud, puntoActual.lugar.longitud);
    
    if (distancia <= radioProximidad) {
        mostrarPistaYPrueba(puntoActual);
    }
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

// Función para iniciar el seguimiento de ubicación
function iniciarSeguimientoUbicacion() {
    if ("geolocation" in navigator) {
        // Crear un icono personalizado para el usuario
        const iconoUsuario = L.divIcon({
            className: 'usuario-marker',
            html: '<i class="fas fa-user"></i>',
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });

        // Opciones de geolocalización
        const opcionesGeo = {
            enableHighAccuracy: true, // Alta precisión
            maximumAge: 0, // No usar caché
            timeout: 5000 // Timeout en 5 segundos
        };

        // Iniciar el seguimiento continuo
        watchId = navigator.geolocation.watchPosition(
            // Éxito
            (position) => {
                const { latitude, longitude } = position.coords;

                // Si el marcador no existe, créalo
                if (!marcadorUsuario) {
                    marcadorUsuario = L.marker([latitude, longitude], {
                        icon: iconoUsuario
                    }).addTo(mapa);
                    
                    // Centrar el mapa en la posición inicial del usuario
                    mapa.setView([latitude, longitude], 15);
                } else {
                    // Actualizar la posición del marcador existente
                    marcadorUsuario.setLatLng([latitude, longitude]);
                }

                // Enviar la posición al servidor
                actualizarPosicionEnServidor(latitude, longitude);
            },
            // Error
            (error) => {
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        mostrarError("Necesitamos permiso para acceder a tu ubicación");
                        break;
                    case error.POSITION_UNAVAILABLE:
                        mostrarError("La información de ubicación no está disponible");
                        break;
                    case error.TIMEOUT:
                        mostrarError("Se agotó el tiempo de espera para obtener la ubicación");
                        break;
                    default:
                        mostrarError("Error desconocido al obtener la ubicación");
                        break;
                }
            },
            opcionesGeo
        );
    } else {
        mostrarError("Tu navegador no soporta geolocalización");
    }
}

// Función para actualizar la posición en el servidor
function actualizarPosicionEnServidor(latitude, longitude) {
    const gimcanaId = document.querySelector('meta[name="gimcana-id"]').content;
    
    fetch('/cliente/actualizar-posicion', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            latitud: latitude,
            longitud: longitude,
            gimcana_id: gimcanaId
        })
    })
    .catch(error => console.error('Error al actualizar posición:', error));
}

// Función para detener el seguimiento de ubicación
function detenerSeguimientoUbicacion() {
    if (watchId !== null) {
        navigator.geolocation.clearWatch(watchId);
        watchId = null;
    }
}

// Asegurarse de detener el seguimiento cuando se cierre la página
window.addEventListener('beforeunload', detenerSeguimientoUbicacion);

// Función para iniciar la gimcana
function iniciarGimcana(gimcanaId) {
    cargarLugaresGimcana(gimcanaId);
    cargarInformacionGrupo(gimcanaId);
    iniciarActualizacionPosiciones(gimcanaId);
}

// Función para cargar los lugares de la gimcana
function cargarLugaresGimcana() {
    if (!gimcanaId) {
        mostrarError('No se ha encontrado el ID de la gimcana');
        return;
    }

    fetch(`/cliente/gimcanas/${gimcanaId}/lugares`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener los lugares');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                lugares = data.lugares;
                mostrarLugaresEnMapa();
                actualizarProgresoGimcana();
            } else {
                throw new Error(data.message || 'Error al cargar los lugares');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarError('Error al cargar los lugares de la gimcana');
        });
}

// Función para verificar la proximidad a los lugares
function verificarProximidadLugares(lat, lng) {
    if (lugares.length > lugarActual) {
        const lugarSiguiente = lugares[lugarActual];
        const distancia = calcularDistancia(lat, lng, lugarSiguiente.latitud, lugarSiguiente.longitud);
        
        if (distancia <= 20) { // 20 metros de radio
            marcarLugarCompletado(lugarSiguiente.id);
        }
    }
}

// Función para marcar un lugar como completado
function marcarLugarCompletado(lugarId) {
    const gimcanaId = document.querySelector('meta[name="gimcana-id"]').content;
    
    fetch('/cliente/gimcanas/marcar-lugar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            lugar_id: lugarId,
            gimcana_id: gimcanaId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            lugarActual++;
            actualizarProgresoGimcana();
            mostrarLugaresEnMapa();
            
            if (lugarActual >= lugares.length) {
                finalizarGimcana();
            }
        }
    });
}

// Función para actualizar las posiciones de todos los usuarios
function actualizarPosicionesUsuarios() {
    if (!gimcanaId) {
        console.error('No se encontró el ID de la gimcana');
        return;
    }

    fetch(`/cliente/gimcanas/${gimcanaId}/posiciones-usuarios`)
        .then(response => {
            if (!response.ok) throw new Error('Error al obtener posiciones');
            return response.json();
        })
        .then(data => {
            if (data.success && data.usuarios) {
                data.usuarios.forEach(usuario => {
                    const usuarioId = usuario.id;
                    const usuarioActual = document.querySelector('meta[name="user-id"]').content;
                    
                    // No actualizar el marcador del usuario actual, ya que se maneja con geolocalización
                    if (usuarioId !== usuarioActual) {
                        actualizarMarcadorUsuario(usuario);
                    }
                });
            }
        })
        .catch(error => console.error('Error al actualizar posiciones:', error));
}

// Función para actualizar el marcador de un usuario
function actualizarMarcadorUsuario(usuario) {
    const marcadorId = `marcador-usuario-${usuario.id}`;
    let marcador = marcadores.find(m => m.id === marcadorId);

    const iconoUsuario = L.divIcon({
        className: 'usuario-marker',
        html: `<div style="background-color: ${usuario.color || '#ff4444'}">
                <i class="fas fa-user"></i>
                <span>${usuario.nombre || 'Usuario'}</span>
              </div>`,
        iconSize: [30, 30],
        iconAnchor: [15, 15]
    });

    if (!marcador) {
        // Crear nuevo marcador si no existe
        marcador = L.marker([usuario.latitud, usuario.longitud], {
            icon: iconoUsuario,
            id: marcadorId
        }).addTo(mapa);
        marcadores.push(marcador);
    } else {
        // Actualizar posición del marcador existente
        marcador.setLatLng([usuario.latitud, usuario.longitud]);
    }
}

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
