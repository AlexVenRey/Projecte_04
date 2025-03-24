// Variables globales
let map;
let markers = [];
let currentPosition = null;
let routingControl = null;
let activeFilters = {
    etiquetas: new Set(),
    favoritos: false,
    cercanos: false
};

// Inicializar el mapa cuando se carga la página
document.addEventListener('DOMContentLoaded', () => {
    initMap();
    cargarLugares();
    setupEventListeners();
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
                L.marker(currentPosition, {
                    icon: L.icon({
                        iconUrl: '/img/marker-icon.png',
                        iconSize: [32, 32],
                        iconAnchor: [16, 32],
                        popupAnchor: [0, -32]
                    })
                }).addTo(map);
            },
            error => console.error('Error getting location:', error)
        );
    }
}

// Configurar los event listeners
function setupEventListeners() {
    document.getElementById('mostrarTodos').addEventListener('click', () => {
        activeFilters.favoritos = false;
        activeFilters.cercanos = false;
        cargarLugares();
    });

    document.getElementById('mostrarFavoritos').addEventListener('click', () => {
        activeFilters.favoritos = true;
        activeFilters.cercanos = false;
        cargarFavoritos();
    });

    document.getElementById('buscarCercanos').addEventListener('click', () => {
        const distancia = document.getElementById('distancia').value;
        if (distancia && currentPosition) {
            activeFilters.cercanos = true;
            activeFilters.favoritos = false;
            buscarLugaresCercanos(distancia);
        } else {
            alert('Por favor, ingresa una distancia y permite el acceso a tu ubicación');
        }
    });
}

// Cargar todos los lugares
async function cargarLugares() {
    try {
        const response = await fetch('/cliente/lugares');
        const lugares = await response.json();
        mostrarLugares(lugares);
    } catch (error) {
        console.error('Error cargando lugares:', error);
    }
}

// Cargar favoritos
async function cargarFavoritos() {
    try {
        const response = await fetch('/cliente/favoritos');
        const favoritos = await response.json();
        mostrarLugares(favoritos);
    } catch (error) {
        console.error('Error cargando favoritos:', error);
    }
}

// Buscar lugares cercanos
async function buscarLugaresCercanos(distancia) {
    if (!currentPosition) return;

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
        mostrarLugares(lugares);
    } catch (error) {
        console.error('Error buscando lugares cercanos:', error);
    }
}

// Mostrar lugares en el mapa y en la lista
function mostrarLugares(lugares) {
    // Limpiar marcadores existentes
    markers.forEach(marker => marker.remove());
    markers = [];

    // Limpiar lista de lugares
    const listaLugares = document.getElementById('lista-lugares');
    listaLugares.innerHTML = '';

    // Mostrar lugares filtrados por etiquetas
    lugares.forEach(lugar => {
        if (activeFilters.etiquetas.size === 0 || 
            lugar.etiquetas.some(e => activeFilters.etiquetas.has(e.id))) {
            agregarMarcador(lugar);

            // Crear elemento de lista
            const item = document.createElement('div');
            item.className = 'list-group-item';
            item.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-1">${lugar.nombre}</h6>
                    <button class="btn-favorito" onclick="toggleFavorito(${lugar.id})">
                        <i class="fas fa-heart${lugar.es_favorito ? ' text-danger' : ''}"></i>
                    </button>
                </div>
                <p class="mb-1">${lugar.descripcion}</p>
                <div class="etiquetas-lista">
                    ${lugar.etiquetas.map(e => `<span class="tag">${e.nombre}</span>`).join('')}
                </div>
            `;
            
            item.addEventListener('click', () => {
                map.setView([lugar.latitud, lugar.longitud], 16);
                markers.find(marker => marker._latlng.lat === lugar.latitud && marker._latlng.lng === lugar.longitud).openPopup();
                mostrarDetalles(lugar);
            });

            listaLugares.appendChild(item);
        }
    });
}

function agregarMarcador(lugar) {
    const marker = L.marker([lugar.latitud, lugar.longitud], {
        icon: L.icon({
            iconUrl: `/img/${lugar.icono}`,
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        })
    });

    marker.bindPopup(`
        <div class="lugar-popup">
            <h5>${lugar.nombre}</h5>
            <p>${lugar.descripcion}</p>
            <p><i class="fas fa-map-marker-alt"></i> ${lugar.direccion}</p>
            <div class="popup-actions">
                <button onclick="toggleFavorito(${lugar.id})" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-heart"></i> Favorito
                </button>
                <button onclick="mostrarRuta(${JSON.stringify(lugar)})" class="btn btn-sm btn-outline-success">
                    <i class="fas fa-route"></i> Ruta
                </button>
            </div>
        </div>
    `);

    marker.addTo(map);
    markers.push(marker);
}

// Crear contenido del popup
function crearPopupContent(lugar) {
    return `
        <div class="lugar-popup">
            <h5>${lugar.nombre}</h5>
            <p>${lugar.descripcion}</p>
            <button class="btn btn-sm btn-primary" onclick="mostrarDetalles(${JSON.stringify(lugar)})">
                Ver más
            </button>
        </div>
    `;
}

// Mostrar detalles en modal
function mostrarDetalles(lugar) {
    const modal = new bootstrap.Modal(document.getElementById('detallesModal'));
    const modalTitle = document.querySelector('#detallesModal .modal-title');
    const modalBody = document.querySelector('#detallesModal .modal-body');

    modalTitle.textContent = lugar.nombre;
    modalBody.innerHTML = `
        <img src="/storage/${lugar.imagen}" alt="${lugar.nombre}" class="img-fluid">
        <p>${lugar.descripcion}</p>
        <div class="etiquetas-lista">
            ${lugar.etiquetas.map(e => `<span class="tag">${e.nombre}</span>`).join('')}
        </div>
    `;

    document.getElementById('btnRuta').onclick = () => mostrarRuta(lugar);
    modal.show();
}

// Mostrar ruta hasta el lugar
function mostrarRuta(lugar) {
    if (!currentPosition) {
        alert('No se puede obtener tu ubicación actual');
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
        router: L.Routing.osrmv1({
            serviceUrl: 'https://router.project-osrm.org/route/v1'
        }),
        lineOptions: {
            styles: [{ color: lugar.color_marcador || '#0d6efd', opacity: 0.8, weight: 5 }]
        }
    }).addTo(map);
}

// Toggle favorito
async function toggleFavorito(lugarId) {
    try {
        const response = await fetch(`/cliente/favoritos/toggle/${lugarId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        const data = await response.json();
        
        // Actualizar la vista según el filtro activo
        if (activeFilters.favoritos) {
            cargarFavoritos();
        } else if (activeFilters.cercanos) {
            const distancia = document.getElementById('distancia').value;
            buscarLugaresCercanos(distancia);
        } else {
            cargarLugares();
        }
    } catch (error) {
        console.error('Error al toggle favorito:', error);
    }
}

function limpiarMapa() {
    markers.forEach(marker => marker.remove());
    markers = [];
    if (routingControl) {
        map.removeControl(routingControl);
    }
}
