// Variables globales
let mapa;
let marcadores = [];
let rutas = [];
let lugares = [];
let puntosControl = [];
let grupoActual = null;
let marcadorUsuario = null;
let circuloProximidad = null;
let watchId = null;
let gimcanaId = null;
let puntoControlActual = null;
let radioProximidad = 50;
let siguiendoUsuario = true;
let ultimaAlertaMostrada = 0;
let modoPrueba = false; // Variable para modo prueba
let puntosCompletados = new Set(); // Conjunto para almacenar IDs de puntos completados

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    gimcanaId = document.querySelector('meta[name="gimcana-id"]').content;
    
    // Inicializar mapa
    mapa = L.map('mapa').setView([41.3851, 2.1734], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mapa);

    // Evento de clic en el mapa para modo prueba
    mapa.on('click', function(e) {
        if (!modoPrueba) {
            modoPrueba = confirm("¿Activar modo prueba? Al hacer clic establecerás tu ubicación.");
            if (!modoPrueba) return;
        }
        
        // Detener seguimiento GPS si está activo
        if (watchId) {
            navigator.geolocation.clearWatch(watchId);
            watchId = null;
        }
        
        actualizarUbicacionManual(e.latlng.lat, e.latlng.lng);
    });

    // Inicializar componentes
    cargarInformacionGrupo();
    cargarPuntoControlActual();
    iniciarSeguimientoUbicacion();
    
    // Verificar ganador cada 5 segundos
    setInterval(verificarGrupoGanador, 5000);

    // Verificar inmediatamente si hay un ganador al cargar
    verificarGrupoGanador();
});

// Función para verificar si hay un grupo ganador
function verificarGrupoGanador() {
    if (!gimcanaId) return;

    fetch(`/cliente/gimcanas/${gimcanaId}/verificar-ganador`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            console.log('Verificación de ganador:', data);
            if (data.success && data.grupo_ganador && !localStorage.getItem(`ganador_mostrado_${gimcanaId}`)) {
                mostrarGrupoGanador(data.grupo_ganador);
            }
        })
        .catch(error => {
            console.error('Error al verificar grupo ganador:', error);
        });
}

function mostrarGrupoGanador(grupoGanador) {
    Swal.fire({
        icon: 'success',
        title: '¡Tenemos un ganador!',
        html: `
            <div class="text-center">
                <h3 class="mb-4">¡El grupo "${grupoGanador.nombre}" ha ganado la gimcana!</h3>
                <p class="mb-3">Miembros del grupo ganador:</p>
                <div class="mb-4">
                    ${grupoGanador.usuarios.map(usuario => 
                        `<span class="badge bg-success me-2 mb-2">${usuario.nombre}</span>`
                    ).join('')}
                </div>
                <p>Tiempo total: ${grupoGanador.tiempo_total}</p>
            </div>
        `,
        confirmButtonText: 'Volver al inicio',
        confirmButtonColor: '#28a745',
        allowOutsideClick: false
    }).then(() => {
        localStorage.setItem(`ganador_mostrado_${gimcanaId}`, 'true');
        window.location.href = '/cliente/gimcanas';
    });
}

// Función para actualizar ubicación manualmente (modo prueba)
function actualizarUbicacionManual(latitud, longitud) {
    const iconoUsuario = L.divIcon({
        className: 'usuario-marker',
        html: '<div class="marcador-usuario"></div>',
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });

    // Actualizar marcador del usuario
    if (!marcadorUsuario) {
        marcadorUsuario = L.marker([latitud, longitud], {
            icon: iconoUsuario
        }).addTo(mapa);

        circuloProximidad = L.circle([latitud, longitud], {
            radius: radioProximidad,
            color: '#2196F3',
            fillOpacity: 0.1
        }).addTo(mapa);
    } else {
        marcadorUsuario.setLatLng([latitud, longitud]);
        circuloProximidad.setLatLng([latitud, longitud]);
    }

    // Actualizar servidor y verificar proximidad
    actualizarPosicionUsuario(latitud, longitud);
    verificarProximidadPuntoControl(latitud, longitud);
    
    // Centrar vista si estamos siguiendo al usuario
    if (siguiendoUsuario) {
        mapa.setView([latitud, longitud], 18);
    }
}

// Modificar la función iniciarSeguimientoUbicacion
function iniciarSeguimientoUbicacion() {
    if (!navigator.geolocation) {
        mostrarError("Tu navegador no soporta geolocalización");
        return;
    }

    const iconoUsuario = L.divIcon({
        className: 'usuario-marker',
        html: '<div class="marcador-usuario"></div>',
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });

    const opcionesGPS = {
        enableHighAccuracy: true,
        maximumAge: 0,
        timeout: 5000
    };

    watchId = navigator.geolocation.watchPosition(
        function(position) {
            const { latitude, longitude } = position.coords;
            console.log('Nueva posición GPS:', latitude, longitude);

            // Actualizar marcador del usuario
            if (!marcadorUsuario) {
                marcadorUsuario = L.marker([latitude, longitude], {
                    icon: iconoUsuario
                }).addTo(mapa);

                circuloProximidad = L.circle([latitude, longitude], {
                    radius: radioProximidad,
                    color: '#2196F3',
                    fillOpacity: 0.1
                }).addTo(mapa);

                mapa.setView([latitude, longitude], 18);
            } else {
                marcadorUsuario.setLatLng([latitude, longitude]);
                circuloProximidad.setLatLng([latitude, longitude]);
            }

            if (siguiendoUsuario) {
                mapa.setView([latitude, longitude]);
            }

            verificarProximidadPuntoControl(latitude, longitude);
            actualizarPosicionUsuario(latitude, longitude);
        },
        function(error) {
            console.error('Error GPS:', error);
            if (error.code !== error.TIMEOUT) {
                mostrarError("Error de geolocalización: " + error.message);
            }
        },
        opcionesGPS
    );
}

// Función para actualizar la posición del usuario en el servidor
function actualizarPosicionUsuario(latitud, longitud) {
    if (!gimcanaId) return;

    fetch('/cliente/actualizar-posicion', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            gimcana_id: gimcanaId,
            latitud: latitud,
            longitud: longitud
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .catch(error => console.error('Error al actualizar posición:', error));
}

function cargarInformacionGrupo() {
    if (!gimcanaId) return;
    
    fetch(`/cliente/gimcanas/${gimcanaId}/grupo-actual`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.grupo) {
                grupoActual = data.grupo;
                actualizarPanelGrupo();
            }
        })
        .catch(error => console.error('Error al cargar grupo:', error));
}

function cargarPuntoControlActual() {
    if (!gimcanaId) return;
    
    fetch(`/cliente/gimcanas/${gimcanaId}/siguiente-punto`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.data) {
                    puntoControlActual = data.data;
                    if (data.completado) {
                        puntosCompletados.add(puntoControlActual.id);
                    }
                    mostrarPuntosControlEnMapa();
                    actualizarProgresoGimcana();
                } else {
                    // La gimcana está completada
                    Swal.fire({
                        icon: 'success',
                        title: '¡Felicidades!',
                        text: '¡Has completado todos los puntos de control!',
                        confirmButtonColor: '#28a745',
                        confirmButtonText: 'Volver al inicio'
                    }).then(() => {
                        window.location.href = '/cliente/gimcanas';
                    });
                }
            }
        })
        .catch(error => {
            console.error('Error al cargar punto de control:', error);
            mostrarError('Error al cargar el punto de control: ' + error.message);
        });
}

function verificarProximidadPuntoControl(latitud, longitud) {
    if (!puntoControlActual?.lugar) {
        console.log('No hay punto de control actual o no tiene lugar asociado');
        return;
    }

    // Si el punto ya está completado, no mostrar el SweetAlert
    if (puntosCompletados.has(puntoControlActual.id)) {
        console.log('Punto ya completado:', puntoControlActual.id);
        return;
    }

    const distancia = calcularDistancia(
        latitud, 
        longitud, 
        puntoControlActual.lugar.latitud,
        puntoControlActual.lugar.longitud
    );

    console.log('Distancia al punto de control:', distancia, 'metros');

    if (distancia <= radioProximidad && (Date.now() - ultimaAlertaMostrada) > 10000) {
        console.log('Mostrando prueba para punto:', puntoControlActual.id);
        ultimaAlertaMostrada = Date.now();
        mostrarPistaYPrueba(puntoControlActual);
    }
}

function mostrarPistaYPrueba(puntoControl) {
    if (!puntoControl || !puntoControl.lugar || !puntoControl.prueba) {
        console.error('Datos de punto de control incompletos:', puntoControl);
        return;
    }

    console.log('Mostrando SweetAlert para punto de control:', puntoControl);

    Swal.fire({
        title: '¡Prueba!',
        html: `
            <div class="text-start">
                <h5 class="mb-3">Ubicación actual:</h5>
                <p class="mb-4">${puntoControl.lugar.nombre}</p>
                
                <h5 class="mb-3">Pista:</h5>
                <p class="mb-4">${puntoControl.pista}</p>
                
                <h5 class="mb-3">Prueba a resolver:</h5>
                <p class="mb-4">${puntoControl.prueba.descripcion}</p>

                <div class="form-group">
                    <label for="respuestaPrueba" class="form-label">Tu respuesta:</label>
                    <input type="text" class="form-control" id="respuestaPrueba" required>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Enviar respuesta',
        cancelButtonText: 'Cerrar',
        confirmButtonColor: '#28a745',
        width: '600px',
        allowOutsideClick: false,
        didOpen: () => {
            document.getElementById('respuestaPrueba').focus();
        },
        preConfirm: () => {
            const respuesta = document.getElementById('respuestaPrueba').value;
            if (!respuesta) {
                Swal.showValidationMessage('Por favor, introduce una respuesta');
                return false;
            }
            return respuesta;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            verificarRespuesta(result.value);
        }
    });
}

function verificarRespuesta(respuesta) {
    if (!puntoControlActual || !puntoControlActual.id || !gimcanaId) {
        console.error('Datos de punto de control no válidos');
        mostrarError('Error: Datos de punto de control no válidos');
        return;
    }

    fetch('/cliente/gimcanas/verificar-prueba', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            punto_control_id: puntoControlActual.id,
            respuesta: respuesta,
            gimcana_id: gimcanaId
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Respuesta del servidor:', data);
        
        if (data.success) {
            // Marcar el punto como completado
            puntosCompletados.add(puntoControlActual.id);
            
            Swal.fire({
                icon: 'success',
                title: '¡Correcto!',
                text: data.message || '¡Has superado esta prueba!',
                confirmButtonColor: '#28a745'
            }).then(() => {
                if (data.gimcana_completada) {
                    // Si la gimcana está completada, mostrar mensaje final
                    Swal.fire({
                        icon: 'success',
                        title: '¡Felicidades!',
                        text: '¡Has completado todos los puntos de control!',
                        confirmButtonColor: '#28a745',
                        confirmButtonText: 'Continuar'
                    }).then(() => {
                        // Redirigir a la lista de gimcanas
                        window.location.href = '/cliente/gimcanas';
                    });
                } else {
                    cargarPuntoControlActual();
                    actualizarProgresoGimcana();
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Respuesta incorrecta',
                text: data.message || 'Inténtalo de nuevo',
                confirmButtonColor: '#dc3545'
            }).then(() => {
                if (!data.message?.includes('Ya has completado')) {
                    mostrarPistaYPrueba(puntoControlActual);
                }
            });
        }
    })
    .catch(error => {
        console.error('Error al verificar respuesta:', error);
        mostrarError('Error al verificar la respuesta: ' + error.message);
    });
}

function mostrarGimcanaCompletada() {
    Swal.fire({
        icon: 'success',
        title: '¡Has completado la gimcana!',
        text: 'Tu grupo ha completado todos los puntos de control.',
        confirmButtonText: 'Finalizar',
        confirmButtonColor: '#28a745',
        allowOutsideClick: false
    }).then(() => {
        window.location.href = '/cliente/gimcanas';
    });
}

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

function mostrarError(mensaje) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: mensaje
    });
}

// Función para mostrar los puntos de control en el mapa
function mostrarPuntosControlEnMapa() {
    limpiarMarcadores();

    if (puntoControlActual && puntoControlActual.lugar) {
        const marcador = L.marker([
            puntoControlActual.lugar.latitud,
            puntoControlActual.lugar.longitud
        ], {
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

        // Centrar el mapa en el punto de control si no hay marcador de usuario
        if (!marcadorUsuario) {
            mapa.setView([
                puntoControlActual.lugar.latitud,
                puntoControlActual.lugar.longitud
            ], 15);
        }
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
            verificarRespuesta();
        }
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

// Función para mostrar mensajes de éxito
function mostrarExito(mensaje) {
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: mensaje
    });
}

function actualizarProgresoGimcana() {
    if (!gimcanaId) return;

    fetch(`/cliente/gimcanas/${gimcanaId}/progreso`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar todas las versiones
                document.querySelectorAll('[id^="puntos-completados"]').forEach(el => {
                    el.textContent = data.completados;
                });
                document.querySelectorAll('[id^="total-puntos"]').forEach(el => {
                    el.textContent = data.total;
                });
                
                // Actualizar todas las barras de progreso
                const porcentaje = (data.completados / data.total) * 100;
                document.querySelectorAll('.progress-bar').forEach(bar => {
                    bar.style.width = `${porcentaje}%`;
                });
            }
        });
}

function mostrarPistaMovil() {
    Swal.fire({
        title: '¡Prueba!',
        html: `
            <div class="text-start">
                <p class="mb-2 small">${puntoControlActual.pista}</p>
                <p class="mb-3">${puntoControlActual.prueba.descripcion}</p>
                <input type="text" 
                       class="form-control" 
                       placeholder="Escribe tu respuesta..."
                       id="respuestaPruebaMovil"
                       autocomplete="off">
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Enviar',
        cancelButtonText: 'Cancelar',
        customClass: {
            popup: 'swal-movil',
            input: 'movil-input'
        },
        didOpen: () => {
            document.getElementById('respuestaPruebaMovil').focus();
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const respuesta = document.getElementById('respuestaPruebaMovil').value;
            verificarRespuesta(respuesta);
        }
    });
}