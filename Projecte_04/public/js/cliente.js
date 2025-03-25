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
let favoritos = new Set(JSON.parse(localStorage.getItem('favoritos') || '[]'));

// Inicializar el mapa cuando se carga la página
document.addEventListener('DOMContentLoaded', () => {
    initMap();
    cargarLugares();
    setupEventListeners();
    cargarEtiquetas();
});

// Inicializar el mapa
function initMap() {
    map = L.map('mapa').setView([41.3479, 2.1045], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

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
                console.error('Error getting location:', error);
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

    return `
        <div class="popup-content">
            <h5>${lugar.nombre}</h5>
            <p class="small">${lugar.creador ? 'Creado por: ' + lugar.creador.nombre : ''}</p>
            <p>${lugar.descripcion}</p>
            <div class="etiquetas">
                ${etiquetasHtml}
            </div>
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
        }
    } catch (error) {
        console.error('Error:', error);
        alert(error.message || 'Hubo un error al actualizar favoritos');
    }
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
}

// Limpiar mapa
function limpiarMapa() {
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];
    if (routingControl) {
        map.removeControl(routingControl);
        routingControl = null;
    }
}