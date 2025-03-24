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
                L.marker(currentPosition, {
                    icon: L.divIcon({
                        html: '<i class="fas fa-user-location fa-2x"></i>',
                        iconSize: [20, 20],
                        className: 'custom-div-icon'
                    })
                }).addTo(map);
            },
            error => console.error('Error getting location:', error)
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
            option.innerHTML = `<i class="fas ${etiqueta.icono}"></i> ${etiqueta.nombre}`;
            select.appendChild(option);
        });
    } catch (error) {
        console.error('Error cargando etiquetas:', error);
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

// Mostrar lugares en el mapa
function mostrarLugares(lugares) {
    limpiarMapa();
    lugares.forEach(lugar => {
        if (activeFilters.etiquetas.size === 0 || 
            lugar.etiquetas.some(e => activeFilters.etiquetas.has(e.id))) {
            agregarMarcador(lugar);
        }
    });
}

// Agregar marcador al mapa
function agregarMarcador(lugar) {
    const icono = lugar.etiquetas.length > 0 ? lugar.etiquetas[0].icono : 'fa-map-marker-alt';
    const marker = L.marker([lugar.latitud, lugar.longitud], {
        icon: L.divIcon({
            html: `<i class="fas ${icono}" style="color: ${lugar.color_marcador}"></i>`,
            iconSize: [20, 20],
            className: 'custom-div-icon'
        })
    });

    marker.bindPopup(crearPopupContent(lugar));
    marker.on('click', () => mostrarDetalles(lugar));
    marker.addTo(map);
    markers.push(marker);
}

// Crear contenido del popup
function crearPopupContent(lugar) {
    const etiquetas = lugar.etiquetas.map(e => 
        `<span class="badge bg-secondary"><i class="fas ${e.icono}"></i> ${e.nombre}</span>`
    ).join(' ');
    
    return `
        <div>
            <h5>${lugar.nombre}</h5>
            <p>${lugar.descripcion}</p>
            <div>${etiquetas}</div>
        </div>
    `;
}

// Mostrar detalles en modal
function mostrarDetalles(lugar) {
    const modal = document.getElementById('detallesModal');
    const title = modal.querySelector('.modal-title');
    const body = modal.querySelector('.modal-body');
    
    title.textContent = lugar.nombre;
    body.innerHTML = `
        <p>${lugar.descripcion}</p>
        <div class="etiquetas">
            ${lugar.etiquetas.map(e => 
                `<span class="badge bg-secondary"><i class="fas ${e.icono}"></i> ${e.nombre}</span>`
            ).join(' ')}
        </div>
        <button class="btn btn-sm btn-${lugar.es_favorito ? 'danger' : 'success'}" 
                onclick="toggleFavorito(${lugar.id})">
            <i class="fas fa-heart"></i> ${lugar.es_favorito ? 'Quitar de favoritos' : 'Añadir a favoritos'}
        </button>
    `;
    
    const btnRuta = modal.querySelector('#btnRuta');
    btnRuta.onclick = () => mostrarRuta(lugar);
    
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
        language: 'es'
    }).addTo(map);
}

// Toggle favorito
async function toggleFavorito(lugarId) {
    try {
        const response = await fetch(`/cliente/favoritos/${lugarId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (response.ok) {
            if (activeFilters.favoritos) {
                cargarFavoritos();
            } else {
                cargarLugares();
            }
            bootstrap.Modal.getInstance(document.getElementById('detallesModal')).hide();
        }
    } catch (error) {
        console.error('Error al modificar favorito:', error);
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
