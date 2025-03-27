// Variables globales
let map;
let markers = [];
let currentPosition = null;
let routingControl = null;
let currentLugar = null;
let activeFilters = {
    etiquetas: new Set(),
    favoritos: false,
    cercanos: false
};
let favoritos = new Set(); // La inicializamos vacía

// Inicializar el mapa cuando se carga la página
document.addEventListener('DOMContentLoaded', () => {
    initMap();
    cargarLugares();
    setupEventListeners();
    cargarEtiquetas();
});

// Inicializar el mapa
function initMap() {
    map = L.map('mapa').setView([41.3879, 2.16992], 13); // Coordenadas iniciales

    // Cargar el mapa base
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    // Evento para capturar clics en el mapa
    map.on('click', function (e) {
        const { lat, lng } = e.latlng;

        // Mostrar el modal y rellenar los campos de latitud y longitud
        document.getElementById('pointLat').value = lat.toFixed(6);
        document.getElementById('pointLng').value = lng.toFixed(6);
        const addPointModal = new bootstrap.Modal(document.getElementById('addPointModal'));
        addPointModal.show();
    });

    // Obtener la ubicación del usuario
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            position => {
                currentPosition = [position.coords.latitude, position.coords.longitude];

                // Añadir marcador de ubicación actual
                const userMarker = L.marker(currentPosition, {
                    icon: L.divIcon({
                        html: '<i class="fas fa-user-circle fa-2x" style="color: #2196F3;"></i>',
                        className: 'user-location-marker',
                        iconSize: [25, 25],
                        iconAnchor: [12, 12]
                    })
                }).addTo(map);
                userMarker.bindPopup('Tu ubicación actual');

                // Centrar el mapa en la ubicación del usuario
                map.setView(currentPosition, 15);
            },
            error => {
                console.error('Error obteniendo la ubicación:', error);
                alert('No se pudo obtener tu ubicación. Algunas funciones pueden no estar disponibles.');
            }
        );
    }
}

// Cargar etiquetas para el filtro
async function cargarEtiquetas() {
    try {
        const response = await fetch('/cliente/etiquetas');
        const etiquetas = await response.json();
        const select = document.getElementById('filtroEtiquetas');
        
        etiquetas.forEach(etiqueta => {
            const option = document.createElement('option');
            option.value = etiqueta.id;
            option.textContent = etiqueta.nombre;
            select.appendChild(option);
        });
    } catch (error) {
        console.error('Error cargando etiquetas:', error);
    }
}

// Función para calcular la distancia entre dos puntos
function calcularDistancia(lat1, lon1, lat2, lon2) {
    const R = 6371000; // Radio de la Tierra en metros
    const φ1 = lat1 * Math.PI / 180;
    const φ2 = lat2 * Math.PI / 180;
    const Δφ = (lat2 - lat1) * Math.PI / 180;
    const Δλ = (lon2 - lon1) * Math.PI / 180;

    const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
             Math.cos(φ1) * Math.cos(φ2) *
             Math.sin(Δλ/2) * Math.sin(Δλ/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

    return R * c; // Distancia en metros
}

// Buscar lugares cercanos
async function buscarLugaresCercanos(distancia) {
    if (!currentPosition) {
        alert('Necesitamos tu ubicación para buscar lugares cercanos. Por favor, permite el acceso a tu ubicación.');
        return;
    }

    try {
        const response = await fetch('/cliente/lugares/cercanos', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                lat: currentPosition[0],
                lng: currentPosition[1],
                distancia: distancia
            })
        });
        
        const lugares = await response.json();

        // Limpiar mapa y mostrar lugares cercanos
        limpiarMapa();
        mostrarLugares(lugares);

        if (lugares.length === 0) {
            alert(`No se encontraron lugares en un radio de ${distancia} metros.`);
        } else {
            const bounds = L.latLngBounds([currentPosition]);
            lugares.forEach(lugar => {
                bounds.extend([lugar.latitud, lugar.longitud]);
            });
            map.fitBounds(bounds, { padding: [50, 50] });
        }
    } catch (error) {
        console.error('Error buscando lugares cercanos:', error);
        alert('Hubo un error al buscar lugares cercanos.');
    }
}

// Configurar los event listeners
function setupEventListeners() {
    // Botón "Todos los lugares"
    document.getElementById('mostrarTodos').addEventListener('click', (e) => {
        e.preventDefault();
        activeFilters.favoritos = false;
        activeFilters.cercanos = false;
        activeFilters.etiquetas.clear();
        
        document.getElementById('mostrarTodos').classList.add('active');
        document.getElementById('mostrarFavoritos').classList.remove('active');
        document.getElementById('filtroEtiquetas').value = '';
        
        cargarLugares();
    });

    // Botón "Mis favoritos"
    document.getElementById('mostrarFavoritos').addEventListener('click', (e) => {
        e.preventDefault();
        activeFilters.favoritos = true;
        activeFilters.cercanos = false;
        
        document.getElementById('mostrarFavoritos').classList.add('active');
        document.getElementById('mostrarTodos').classList.remove('active');
        
        cargarFavoritos();
    });

    // Botón "Buscar cercanos"
    document.getElementById('buscarCercanos').addEventListener('click', () => {
        const distanciaInput = document.getElementById('distancia');
        const distancia = parseInt(distanciaInput.value);

        if (!distancia || distancia <= 0) {
            alert('Por favor, ingresa una distancia válida en metros.');
            return;
        }

        if (!currentPosition) {
            alert('Necesitamos tu ubicación para buscar lugares cercanos. Por favor, permite el acceso a tu ubicación.');
            return;
        }

        activeFilters.cercanos = true;
        activeFilters.favoritos = false;
        buscarLugaresCercanos(distancia);

        document.getElementById('mostrarTodos').classList.remove('active');
        document.getElementById('mostrarFavoritos').classList.remove('active');
    });

    document.getElementById('filtroEtiquetas').addEventListener('change', (e) => {
        const etiquetaId = e.target.value;
        if (etiquetaId) {
            activeFilters.etiquetas.clear();
            activeFilters.etiquetas.add(parseInt(etiquetaId));
        } else {
            activeFilters.etiquetas.clear();
        }
        cargarLugares();
    });
}

async function cargarLugares() {
    try {
        const response = await fetch('/cliente/lugares');
        const result = await response.json();
        
        if (!result.success) {
            throw new Error(result.message || 'Error al cargar lugares');
        }

        // Asegúrate de acceder a result.data
        console.log('Lugares recibidos:', result.data);
        
        if (!Array.isArray(result.data)) {
            throw new Error('La respuesta no contiene un array de lugares');
        }

        mostrarLugares(result.data);
    } catch (error) {
        console.error('Error cargando lugares:', error);
        alert(error.message);
    }
}
// Cargar favoritos
async function cargarFavoritos() {
    try {
<<<<<<< HEAD
        const response = await fetch('/cliente/lugares');
        const result = await response.json();
        
        if (!result.success) {
            throw new Error(result.message || 'Error al cargar lugares');
        }

        // Filtrar solo los favoritos
        const lugaresFavoritos = result.data.filter(lugar => favoritos.has(lugar.id));
        console.log('Favoritos filtrados:', lugaresFavoritos);
        
        mostrarLugares(lugaresFavoritos);
    } catch (error) {
        console.error('Error cargando favoritos:', error);
        alert('Error al cargar favoritos: ' + error.message);
=======
        const response = await fetch('/cliente/mis-favoritos');
        if (!response.ok) {
            throw new Error('Error al cargar favoritos');
        }
        const lugares = await response.json();
        mostrarLugares(lugares);
    } catch (error) {
        console.error('Error cargando favoritos:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al cargar tus favoritos'
        });
>>>>>>> e8ea6b4c56b72a6f66b1711b6fe4febadc98d539
    }
}
function mostrarLugares(lugares) {
    // Verifica que sea un array
    if (!Array.isArray(lugares)) {
        console.error('Los lugares no son un array:', lugares);
        return;
    }

    limpiarMapa();
    
    lugares.forEach(lugar => {
        if (lugar.latitud && lugar.longitud) {
            agregarMarcador(lugar);
        } else {
            console.warn('Lugar sin coordenadas:', lugar);
        }
    });
}
// Agregar marcador al mapa
function agregarMarcador(lugar) {
    const marker = L.marker([lugar.latitud, lugar.longitud], {
        icon: createCustomMarker(lugar)
    });

    marker.bindPopup(createPopupContent(lugar));
    marker.on('click', () => mostrarDetalles(lugar));
    marker.addTo(map);
    markers.push(marker);
}

// Función para crear un marcador personalizado
function createCustomMarker(lugar) {
    const markerHtml = `
        <div class="custom-marker" style="background-color: ${lugar.color_marcador}">
            <i class="fas ${lugar.icono || 'fa-map-marker-alt'}"></i>
        </div>
    `;
    
    return L.divIcon({
        html: markerHtml,
        className: 'custom-marker-container',
        iconSize: [30, 30],
        iconAnchor: [15, 30],
        popupAnchor: [0, -30]
    });
}

// Función para crear el contenido del popup
function createPopupContent(lugar) {
    let etiquetasHtml = '';
    if (lugar.etiquetas) {
        lugar.etiquetas.forEach(etiqueta => {
            etiquetasHtml += `
                <span class="badge bg-primary me-1 mb-1">
                    <i class="fas ${etiqueta.icono}"></i> 
                    ${etiqueta.nombre}
                </span>
            `;
        });
    }

    const esFavorito = lugar.es_favorito; // Este dato vendrá del servidor

    return `
        <div class="popup-content">
            <h5>${lugar.nombre}</h5>
            <p class="small">${lugar.creador ? 'Creado por: ' + lugar.creador.nombre : ''}</p>
            <p>${lugar.descripcion}</p>
            <div class="etiquetas">
                ${etiquetasHtml}
            </div>
<<<<<<< HEAD
=======
            <button onclick="toggleFavorito(${lugar.id})" class="btn btn-sm ${esFavorito ? 'btn-success' : 'btn-outline-danger'}">
                <i class="fas ${esFavorito ? 'fa-check' : 'fa-heart'}"></i> 
                ${esFavorito ? 'Añadido a favoritos' : 'Añadir a favoritos'}
            </button>
>>>>>>> e8ea6b4c56b72a6f66b1711b6fe4febadc98d539
        </div>
    `;
}

// Mostrar detalles en modal
function mostrarDetalles(lugar) {
    currentLugar = lugar;
    const modal = document.getElementById('detallesModal');
    const title = modal.querySelector('.modal-title');
    const body = modal.querySelector('.modal-body');
    const btnFavorito = modal.querySelector('#btnFavorito');
    const btnRuta = modal.querySelector('#btnRuta');
    const btnEliminar = modal.querySelector('#btnEliminar');
    
    title.textContent = lugar.nombre;
    
    let etiquetasHtml = '';
    if (lugar.etiquetas) {
        lugar.etiquetas.forEach(etiqueta => {
            etiquetasHtml += `
                <span class="badge bg-secondary me-1 mb-1">
                    <i class="fas ${etiqueta.icono}"></i> 
                    ${etiqueta.nombre}
                </span>
            `;
        });
    }
    
    body.innerHTML = `
        <p class="text-muted small">${lugar.creador ? 'Creado por: ' + lugar.creador.nombre : ''}</p>
        <p>${lugar.descripcion}</p>
        <div class="etiquetas mb-3">
            ${etiquetasHtml}
        </div>
    `;
    
    // Configurar botón de favoritos
    const esFavorito = favoritos.has(lugar.id);
    btnFavorito.querySelector('i').className = esFavorito ? 'fas fa-heart text-danger' : 'fas fa-heart';
    btnFavorito.querySelector('span').textContent = esFavorito ? 'Quitar de favoritos' : 'Añadir a favoritos';
    
    // Mostrar/ocultar botón de eliminar
    if (lugar.es_propietario) {
        btnEliminar.classList.remove('d-none');
    } else {
        btnEliminar.classList.add('d-none');
    }
    
    // Configurar eventos
    btnFavorito.onclick = () => toggleFavorito(lugar.id);
    btnRuta.onclick = () => mostrarRuta(lugar);
    btnEliminar.onclick = () => eliminarMarcador(lugar.id);
    
    new bootstrap.Modal(modal).show();
}

// Mostrar ruta hasta el lugar
function mostrarRuta(lugar) {
    if (!currentPosition) {
        alert('Por favor, permite el acceso a tu ubicación para ver la ruta');
        return;
    }

    if (routingControl) {
        map.removeControl(routingControl);
    }

    routingControl = L.Routing.control({
        waypoints: [
            L.latLng(currentPosition[0], currentPosition[1]),
            L.latLng(lugar.latitud, lugar.longitud)
        ],
        routeWhileDragging: false,
        showAlternatives: true,
        fitSelectedRoute: true,
        language: 'es',
        router: L.Routing.mapbox('pk.eyJ1IjoiZXZhcmlzdG82NyIsImEiOiJjbThuZm1xYjEwMDlxMnZzYWl4NG01dnU1In0.P3Jq6ts8g-gh3HjD611hcg', {
            profile: 'mapbox/walking'
        }),
        styles: [
            {
                color: '#2196F3',
                weight: 4,
                opacity: 0.8,
                lineJoin: 'round'
            }
        ]
    }).addTo(map);
}

// Toggle favorito
async function toggleFavorito(lugarId) {
    try {
<<<<<<< HEAD
        const response = await fetch(`/cliente/favoritos/${lugarId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            if (data.esFavorito) {
                favoritos.add(lugarId);
            } else {
                favoritos.delete(lugarId);
            }
            
            localStorage.setItem('favoritos', JSON.stringify(Array.from(favoritos)));
            
            // Actualizar UI
            const btnFavorito = document.getElementById('btnFavorito');
            if (btnFavorito) {
                const icon = btnFavorito.querySelector('i');
                const span = btnFavorito.querySelector('span');
                
                icon.className = data.esFavorito ? 'fas fa-heart text-danger' : 'fas fa-heart';
                span.textContent = data.esFavorito ? 'Quitar de favoritos' : 'Añadir a favoritos';
            }
            
            // Si estamos en la vista de favoritos, actualizar
            if (activeFilters.favoritos) {
                cargarFavoritos();
            }
        } else {
            throw new Error(data.message || 'Error al actualizar favoritos');
=======
        const response = await fetch('/cliente/toggle-favorito', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ lugar_id: lugarId })
        });

        const data = await response.json();
        
        if (data.success) {
            // Actualizar el estado del botón
            actualizarBotonFavorito(lugarId, data.esFavorito);
            
            Swal.fire({
                icon: 'success',
                title: data.esFavorito ? '¡Añadido!' : 'Eliminado',
                text: data.message,
                timer: 1500,
                showConfirmButton: false
            });

            // Si estamos en la vista de favoritos, actualizar la lista
            if (activeFilters.favoritos) {
                cargarFavoritos();
            }
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al actualizar tus favoritos'
        });
    }
}

function actualizarBotonFavorito(lugarId, esFavorito) {
    const btnFavorito = document.getElementById('btnFavorito');
    if (btnFavorito) {
        btnFavorito.classList.toggle('btn-success', esFavorito);
        btnFavorito.classList.toggle('btn-outline-danger', !esFavorito);
        const icon = btnFavorito.querySelector('i');
        if (icon) {
            icon.classList.toggle('fa-check', esFavorito);
            icon.classList.toggle('fa-heart', !esFavorito);
        }
        const texto = btnFavorito.querySelector('span');
        if (texto) {
            texto.textContent = esFavorito ? 'Añadido a favoritos' : 'Añadir a favoritos';
>>>>>>> e8ea6b4c56b72a6f66b1711b6fe4febadc98d539
        }
    } catch (error) {
        console.error('Error:', error);
        alert(error.message || 'Hubo un error al actualizar favoritos');
    }
<<<<<<< HEAD
}

// Eliminar marcador
async function eliminarMarcador(lugarId) {
    if (!confirm('¿Estás seguro de que quieres eliminar este marcador?')) {
        return;
    }

    try {
        const response = await fetch(`/cliente/marcadores/${lugarId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (response.ok) {
            alert('Marcador eliminado exitosamente');
            cargarLugares();
            const modal = bootstrap.Modal.getInstance(document.getElementById('detallesModal'));
            modal.hide();
        } else {
            throw new Error(data.message || 'Error al eliminar el marcador');
        }
    } catch (error) {
        console.error('Error:', error);
        alert(error.message || 'No se pudo eliminar el marcador');
    }
=======
>>>>>>> e8ea6b4c56b72a6f66b1711b6fe4febadc98d539
}

// Limpiar mapa
function limpiarMapa() {
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];
    if (routingControl) {
        map.removeControl(routingControl);
        routingControl = null;
    }
<<<<<<< HEAD
}
=======
}

document.addEventListener("DOMContentLoaded", function () {
    initMap();

    // Guardar el punto al hacer clic en "Guardar Punto"
    document.getElementById('savePoint').addEventListener('click', function () {
        const name = document.getElementById('pointName').value.trim();
        const lat = document.getElementById('pointLat').value;
        const lng = document.getElementById('pointLng').value;
        const tags = Array.from(document.getElementById('pointTags').selectedOptions).map(option => option.value);
        const color = document.getElementById('pointColor').value;

        // Limpiar mensajes de error previos
        const errorContainer = document.getElementById('errorContainer');
        if (errorContainer) {
            errorContainer.remove();
        }

        // Validar campos
        const errors = [];
        if (!name) {
            errors.push('El campo "Nombre del Punto" es obligatorio.');
        }
        if (!lat || !lng) {
            errors.push('Debe seleccionar una ubicación en el mapa.');
        }
        if (tags.length === 0) {
            errors.push('Debe seleccionar al menos una etiqueta.');
        }
        if (!color) {
            errors.push('Debe seleccionar un color para el punto.');
        }

        // Mostrar errores si los hay
        if (errors.length > 0) {
            const modalBody = document.querySelector('#addPointModal .modal-body');
            const errorDiv = document.createElement('div');
            errorDiv.id = 'errorContainer';
            errorDiv.className = 'alert alert-danger';
            errorDiv.innerHTML = errors.join('<br>');
            modalBody.prepend(errorDiv);
            return;
        }

        // Enviar el punto al servidor
        fetch('/cliente/puntos', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ nombre: name, latitud: lat, longitud: lng, etiquetas: tags, color_marcador: color }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Punto añadido correctamente.');
                    location.reload(); // Recargar la página para actualizar el mapa
                } else {
                    alert('Error al añadir el punto: ' + data.error);
                }
            })
            .catch(error => console.error('Error:', error));
    });
});
>>>>>>> 8939b8ce9a954f21618fc7e95c3e7bb10c5754af
