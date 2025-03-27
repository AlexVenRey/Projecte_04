// Variable global para almacenar los intervalos de actualización
let intervalosActualizacion = {};

// Función para actualizar la lista de grupos
function actualizarListaGrupos(gimcanaId) {
    return fetch(`/cliente/grupos/${gimcanaId}/miembros`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Datos recibidos:', data); // Para debug

        if (data.success) {
            const listaGrupos = document.querySelector(`#grupoModal${gimcanaId} .lista-grupos`);
            if (!listaGrupos) return;

            listaGrupos.innerHTML = '';

            if (data.grupos && data.grupos.length > 0) {
                data.grupos.forEach(grupo => {
                    const grupoElement = document.createElement('li');
                    grupoElement.className = 'list-group-item';
                    
                    let miembrosHtml = grupo.usuarios.map(usuario => 
                        `<span class="badge bg-secondary me-1">${usuario.nombre}</span>`
                    ).join('');

                    grupoElement.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6>${grupo.nombre}</h6>
                                <div class="miembros-grupo">
                                    ${miembrosHtml}
                                </div>
                            </div>
                            <button class="btn btn-primary" onclick="unirseGrupo(${grupo.id}, ${gimcanaId})">
                                Unirse al Grupo
                            </button>
                        </div>
                    `;
                    listaGrupos.appendChild(grupoElement);
                });
            } else {
                listaGrupos.innerHTML = '<li class="list-group-item">No hay grupos disponibles</li>';
            }
        }
    })
    .catch(error => {
        console.error('Error al actualizar grupos:', error);
        detenerActualizacionAutomatica(gimcanaId); // Detener si hay error
    });
}

// Función para iniciar la actualización automática
function iniciarActualizacionAutomatica(gimcanaId) {
    // Detener cualquier intervalo existente
    detenerActualizacionAutomatica(gimcanaId);
    
    // Crear nuevo intervalo
    intervalosActualizacion[gimcanaId] = setInterval(() => {
        actualizarListaGrupos(gimcanaId);
    }, 2000); // Actualizar cada 2 segundos
}

// Función para detener la actualización automática
function detenerActualizacionAutomatica(gimcanaId) {
    if (intervalosActualizacion[gimcanaId]) {
        clearInterval(intervalosActualizacion[gimcanaId]);
        delete intervalosActualizacion[gimcanaId];
    }
}

function mostrarError(mensaje) {
    Swal.fire({
        icon: 'error',
        title: '¡Error!',
        text: mensaje,
        confirmButtonColor: '#d33'
    });
}

function mostrarExito(mensaje) {
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: mensaje,
        confirmButtonColor: '#28a745'
    });
}

// Función para unirse a un grupo
function unirseGrupo(grupoId, gimcanaId) {
    Swal.fire({
        title: '¿Unirse al grupo?',
        text: '¿Estás seguro de que quieres unirte a este grupo?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, unirme',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('/cliente/unirse-grupo', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    grupo_id: grupoId,
                    gimcana_id: gimcanaId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarExito(data.message);
                    actualizarListaGrupos(gimcanaId);
                } else {
                    mostrarError(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarError('Error al unirse al grupo. Por favor, inténtalo de nuevo.');
            });
        }
    });
}

// Función para crear un nuevo grupo
function crearGrupo(gimcanaId) {
    const nombreGrupo = document.getElementById(`nombreGrupo${gimcanaId}`).value;
    
    if (!nombreGrupo) {
        mostrarError('Por favor, introduce un nombre para el grupo');
        return;
    }

    const botonCrear = document.querySelector(`#crearGrupoModal${gimcanaId} .btn-primary`);
    botonCrear.disabled = true;
    botonCrear.textContent = 'Creando...';

    fetch('/cliente/crear-grupo', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            gimcana_id: gimcanaId,
            nombre: nombreGrupo
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Cerrar el modal de crear grupo
            const modalCrear = bootstrap.Modal.getInstance(document.getElementById(`crearGrupoModal${gimcanaId}`));
            modalCrear.hide();
            
            // Limpiar el campo de nombre
            document.getElementById(`nombreGrupo${gimcanaId}`).value = '';
            
            mostrarExito(data.message);
            actualizarListaGrupos(gimcanaId);
        } else {
            if (data.errors) {
                // Si hay múltiples errores de validación, mostrarlos todos
                const errores = Object.values(data.errors).join('\n');
                mostrarError(errores);
            } else {
                mostrarError(data.message);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarError('Error al crear el grupo. Por favor, inténtalo de nuevo.');
    })
    .finally(() => {
        botonCrear.disabled = false;
        botonCrear.textContent = 'Crear Grupo';
    });
}

// Inicialización cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function() {
    const modalesGrupos = document.querySelectorAll('[id^="grupoModal"]');
    
    modalesGrupos.forEach(modal => {
        const gimcanaId = modal.id.replace('grupoModal', '');
        
        modal.addEventListener('show.bs.modal', function() {
            actualizarListaGrupos(gimcanaId).then(() => {
                iniciarActualizacionAutomatica(gimcanaId);
            });
        });

        modal.addEventListener('hidden.bs.modal', function() {
            detenerActualizacionAutomatica(gimcanaId);
        });
    });
});
