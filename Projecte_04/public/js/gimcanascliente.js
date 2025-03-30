// Variables globales
let intervalosActualizacion = {};
let usuariosListos = new Set();

// Función para validar el nombre del grupo
function validarNombreGrupo(nombre) {
    const regex = /^[a-zA-Z0-9\s]{3,100}$/;
    if (!nombre) {
        return 'El nombre del grupo es obligatorio';
    }
    if (!regex.test(nombre)) {
        return 'El nombre solo puede contener letras, números y espacios (3-100 caracteres)';
    }
    return null;
}

// Función para actualizar la lista de grupos
function actualizarListaGrupos(gimcanaId) {
    fetch(`/cliente/grupos/${gimcanaId}/miembros`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.grupos) {
                const listaGrupos = document.querySelector(`#grupoModal${gimcanaId} .lista-grupos`);
                if (listaGrupos) {
                    actualizarContenidoGrupos(listaGrupos, data.grupos, gimcanaId);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarError('Error al cargar los grupos');
        });
}

// Función para actualizar el contenido de los grupos
function actualizarContenidoGrupos(listaGrupos, grupos, gimcanaId) {
    // Guardar el estado actual de los botones antes de actualizar
    const botonesActuales = document.querySelectorAll(`[id^="btnListo${gimcanaId}"]`);
    const estadosBotones = {};
    botonesActuales.forEach(boton => {
        if (boton.disabled) {
            estadosBotones[boton.id] = true;
        }
    });

    // Actualizar el contenido completo de los grupos
    listaGrupos.innerHTML = grupos.map(grupo => `
        <li class="list-group-item">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6>${grupo.nombre}</h6>
                    <div class="miembros-grupo">
                        ${grupo.usuarios.map(usuario => `
                            <span class="badge bg-secondary me-1">
                                ${usuario.nombre}
                                ${usuario.pivot.esta_listo ? '<i class="fas fa-check-circle text-success"></i>' : ''}
                            </span>
                        `).join('')}
                    </div>
                </div>
                <div class="d-flex gap-2">
                    ${obtenerBotonesGrupo(grupo, gimcanaId)}
                </div>
            </div>
        </li>
    `).join('');

    // Restaurar el estado de los botones
    botonesActuales.forEach(boton => {
        if (estadosBotones[boton.id]) {
            const botonNuevo = document.getElementById(boton.id);
            if (botonNuevo) {
                botonNuevo.disabled = true;
                botonNuevo.innerHTML = '<i class="fas fa-check"></i> ¡Listo!';
                botonNuevo.classList.remove('btn-primary');
                botonNuevo.classList.add('btn-success');
            }
        }
    });
}

// Función para marcar como listo
function marcarListo(grupoId, gimcanaId) {
    const boton = document.querySelector(`#btnListo${grupoId}${gimcanaId}`);
    if (!boton) return;

    // Deshabilitar el botón temporalmente
    boton.disabled = true;
    boton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

    fetch('/cliente/grupos/marcar-listo', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            grupo_id: grupoId,
            gimcana_id: gimcanaId
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Actualizar el estado del botón
            boton.innerHTML = '<i class="fas fa-check"></i> ¡Listo!';
            boton.classList.remove('btn-primary');
            boton.classList.add('btn-success');
            boton.disabled = true;

            // Mostrar mensaje de éxito
            mostrarExito(data.message || '¡Has marcado que estás listo!');

            // Verificar si todos están listos
            verificarTodosListos(gimcanaId);
        } else {
            throw new Error(data.message || 'Error al marcar como listo');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Restaurar el estado original del botón
        boton.disabled = false;
        boton.innerHTML = '<i class="fas fa-check"></i> Marcar como listo';
        boton.classList.remove('btn-success');
        boton.classList.add('btn-primary');
        mostrarError(error.message || 'Error al marcar como listo');
    });
}

// Función para obtener los botones según el estado del usuario
function obtenerBotonesGrupo(grupo, gimcanaId) {
    const usuarioActual = document.querySelector('meta[name="user-id"]').content;
    const usuarioEnGrupo = grupo.usuarios.find(u => u.id == usuarioActual);
    
    if (usuarioEnGrupo) {
        const estaListo = usuarioEnGrupo.pivot.esta_listo;
        return `<button id="btnListo${grupo.id}${gimcanaId}" 
                        class="btn ${estaListo ? 'btn-success' : 'btn-primary'}" 
                        ${estaListo ? 'disabled' : `onclick="marcarListo(${grupo.id}, ${gimcanaId})"`}>
                    <i class="fas fa-check"></i> ${estaListo ? '¡Listo!' : 'Marcar como listo'}
                </button>`;
    } else {
        return `<button class="btn btn-primary" onclick="unirseGrupo(${grupo.id}, ${gimcanaId})"
                        ${grupo.usuarios.length >= 5 ? 'disabled' : ''}>
                    Unirse al grupo
                </button>`;
    }
}

// Función para restaurar el estado de los botones
function restaurarEstadoBotones(gimcanaId) {
    const botones = document.querySelectorAll(`[id^="btnListo${gimcanaId}"]`);
    botones.forEach(boton => {
        const grupoId = boton.id.replace(`btnListo${gimcanaId}`, '');
        if (usuariosListos.has(`${grupoId}${gimcanaId}`)) {
            boton.disabled = true;
            boton.innerHTML = '<i class="fas fa-check"></i> ¡Listo!';
            boton.classList.remove('btn-primary');
            boton.classList.add('btn-success');
        }
    });
}

// Función para iniciar la actualización automática
function iniciarActualizacionAutomatica(gimcanaId) {
    detenerActualizacionAutomatica(gimcanaId);
    
    // Actualizar la lista de grupos cada 2 segundos
    intervalosActualizacion[gimcanaId] = setInterval(() => {
        actualizarListaGrupos(gimcanaId);
        verificarTodosListos(gimcanaId);
    }, 2000); // Cambiado a 2 segundos para reducir la carga
}

// Función para detener la actualización automática
function detenerActualizacionAutomatica(gimcanaId) {
    if (intervalosActualizacion[gimcanaId]) {
        clearInterval(intervalosActualizacion[gimcanaId]);
        delete intervalosActualizacion[gimcanaId];
    }
}

// Función para verificar si todos están listos
function verificarTodosListos(gimcanaId) {
    fetch(`/cliente/gimcanas/${gimcanaId}/verificar-todos-listos`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.todos_listos) {
                // Detener todas las actualizaciones automáticas
                Object.values(intervalosActualizacion).forEach(intervalo => {
                    clearInterval(intervalo);
                });
                intervalosActualizacion = {};

                // Mostrar SweetAlert y redirigir
                Swal.fire({
                    title: '¡Todos listos!',
                    text: 'La gimcana comenzará en breve...',
                    icon: 'success',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timer: 2000,
                    timerProgressBar: true
                }).then(() => {
                    // Iniciar la gimcana
                    fetch(`/cliente/gimcanas/${gimcanaId}/iniciar`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = `/cliente/gimcanas/${gimcanaId}/live`;
                        }
                    });
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarError('Error al verificar el estado de los usuarios');
        });
}

// Función para mostrar el botón de iniciar gimcana
function mostrarBotonIniciar(gimcanaId) {
    const contenedorBoton = document.getElementById(`contenedorBotonIniciar${gimcanaId}`);
    if (contenedorBoton && contenedorBoton.style.display === 'none') {
        contenedorBoton.style.display = 'block';
        const botonIniciar = document.getElementById(`btnIniciarGimcana${gimcanaId}`);
        if (botonIniciar) {
            botonIniciar.innerHTML = '<i class="fas fa-play"></i> ¡Iniciar Gimcana!';
            botonIniciar.disabled = false;
            botonIniciar.onclick = () => iniciarGimcana(gimcanaId);
        }
    }
}

// Función para unirse a un grupo
function unirseGrupo(grupoId, gimcanaId) {
    const boton = document.querySelector(`button[onclick="unirseGrupo(${grupoId}, ${gimcanaId})"]`);
    if (boton) {
        boton.disabled = true;
        boton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uniendo...';
    }

    fetch('/cliente/grupos/unirse-grupo', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            grupo_id: grupoId,
            gimcana_id: gimcanaId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarExito('¡Te has unido al grupo!');
            actualizarListaGrupos(gimcanaId);
        } else {
            throw new Error(data.message || 'Error al unirse al grupo');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (boton) {
            boton.disabled = false;
            boton.innerHTML = 'Unirse al grupo';
        }
        mostrarError(error.message || 'Error al unirse al grupo');
    });
}

// Función para crear un nuevo grupo
function crearGrupo(gimcanaId) {
    const nombreInput = document.getElementById(`nombreGrupo${gimcanaId}`);
    const nombre = nombreInput.value.trim();

    const errorNombre = validarNombreGrupo(nombre);
    if (errorNombre) {
        mostrarError(errorNombre);
        nombreInput.focus();
        return;
    }

    fetch('/cliente/grupos/crear-grupo', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            nombre: nombre,
            gimcana_id: gimcanaId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarExito('¡Grupo creado correctamente!');
            const modal = bootstrap.Modal.getInstance(document.getElementById(`crearGrupoModal${gimcanaId}`));
            modal.hide();
            actualizarListaGrupos(gimcanaId);
        } else {
            if (data.errors) {
                const mensajes = Object.values(data.errors).flat().join('\n');
                mostrarError(mensajes);
            } else {
                mostrarError(data.message);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarError('Error al crear el grupo');
    });
}

// Función para mostrar mensajes de error
function mostrarError(mensaje) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: mensaje,
        confirmButtonColor: '#d33'
    });
}

// Función para mostrar mensajes de éxito
function mostrarExito(mensaje) {
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: mensaje,
        confirmButtonColor: '#28a745'
    });
}

// Inicialización cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function() {
    const modalesGrupos = document.querySelectorAll('[id^="grupoModal"]');
    
    modalesGrupos.forEach(modal => {
        const gimcanaId = modal.id.replace('grupoModal', '');
        
        modal.addEventListener('show.bs.modal', function() {
            actualizarListaGrupos(gimcanaId);
            iniciarActualizacionAutomatica(gimcanaId);
        });

        modal.addEventListener('hidden.bs.modal', function() {
            detenerActualizacionAutomatica(gimcanaId);
        });
    });
});