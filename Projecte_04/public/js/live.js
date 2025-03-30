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
let modoPrueba = false; // Nueva variable para modo de prueba

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    gimcanaId = document.querySelector('meta[name="gimcana-id"]').content;
    
    // Inicializar mapa
    mapa = L.map('mapa').setView([41.3851, 2.1734], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mapa);

    // Aumentar el radio de proximidad a 50 metros para mayor tolerancia
    radioProximidad = 50;

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

    // Cargar información inicial
    cargarInformacionGrupo();
    cargarPuntoControlActual();
    iniciarSeguimientoUbicacion();
});

// Función para actualizar ubicación manualmente
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
    
    // Centrar vista
    if (siguiendoUsuario) {
        mapa.setView([latitud, longitud], 18);
    }
}

// ... (el resto del código original permanece igual a partir de aquí)
// [Todas las demás funciones se mantienen sin cambios]

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
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'Error en la respuesta del servidor');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.data) {
                puntoControlActual = {
                    id: data.data.id,
                    lugar: data.data.lugar,
                    pista: data.data.pista,
                    prueba: data.data.prueba
                };
                mostrarPuntosControlEnMapa();
                actualizarProgresoGimcana();
            } else if (data.success && !data.data) {
                // La gimcana está completada
                Swal.fire({
                    icon: 'success',
                    title: '¡Felicidades!',
                    text: 'Has completado todos los puntos de control'
                });
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

    const distancia = calcularDistancia(
        latitud, 
        longitud, 
        puntoControlActual.lugar.latitud,
        puntoControlActual.lugar.longitud
    );

    console.log('Distancia al punto de control:', distancia, 'metros');
    console.log('Radio de proximidad:', radioProximidad, 'metros');
    console.log('Tiempo desde última alerta:', Date.now() - ultimaAlertaMostrada, 'ms');

    // Si estamos dentro del radio de proximidad y ha pasado suficiente tiempo desde la última alerta
    if (distancia <= radioProximidad && (Date.now() - ultimaAlertaMostrada) > 10000) {
        console.log('Dentro del radio y tiempo suficiente desde última alerta');
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

    console.log('Enviando respuesta:', {
        punto_control_id: puntoControlActual.id,
        respuesta: respuesta,
        gimcana_id: gimcanaId
    });

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
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Error en la respuesta del servidor');
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Respuesta del servidor:', data);
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Correcto!',
                text: data.message || '¡Has superado esta prueba!',
                confirmButtonColor: '#28a745'
            }).then(() => {
                if (data.gimcana_completada) {
                    if (data.grupo_ganador) {
                        mostrarGrupoGanador(data.grupo_ganador);
                    } else {
                        mostrarGimcanaCompletada();
                    }
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
                // Volver a mostrar la prueba solo si no está completada
                if (!data.message?.includes('Ya has completado')) {
                    mostrarPistaYPrueba(puntoControlActual);
                }
            });
        }
    })
    .catch(error => {
        console.error('Error al verificar respuesta:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Error al verificar la respuesta',
            confirmButtonColor: '#dc3545'
        });
    });
}

function mostrarGrupoGanador(grupoGanador) {
    Swal.fire({
        icon: 'success',
        title: '¡Felicidades!',
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
        window.location.href = '/cliente/gimcanas';
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

// ... (variables globales anteriores se mantienen igual)

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

function actualizarPanelGrupo() {
    if (grupoActual) {
        // Actualizar nombre en todas las vistas
        document.querySelectorAll('#nombre-grupo, #nombre-grupo-movil').forEach(el => {
            el.textContent = grupoActual.nombre;
        });
        
        // Actualizar miembros del grupo
        const miembrosHTML = grupoActual.usuarios.map(usuario => `
            <span class="badge bg-secondary me-1 mb-1">
                ${usuario.nombre}
                ${usuario.pivot.esta_listo ? '<i class="fas fa-check-circle text-success ms-1"></i>' : ''}
            </span>
        `).join('');
        
        document.getElementById('miembros-grupo').innerHTML = miembrosHTML;
    }
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

// ... (resto de funciones se mantienen igual hasta el final del archivo)