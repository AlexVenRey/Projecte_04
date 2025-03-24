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
                const userMarker = L.marker(currentPosition, {
                    title: 'Tu ubicación'
                });
                userMarker.addTo(map);
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

    document.getElementById('btnFavorito').addEventListener('click', () => {
        if (currentLugar) {
            toggleFavorito(currentLugar.id);
            const modal = bootstrap.Modal.getInstance(document.getElementById('detallesModal'));
            modal.hide();
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

// Mostrar lugares en el mapa
function mostrarLugares(lugares) {
    limpiarMapa();
    lugares.forEach(lugar => {
        if (activeFilters.etiquetas.size === 0 || 
            lugar.etiquetas.some(e => activeFilters.etiquetas.has(e.id))) {
            if (activeFilters.favoritos && !favoritos.has(lugar.id)) return;
            agregarMarcador(lugar);
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

// Función para crear un marcador personalizado con Font Awesome
function createCustomMarker(lugar) {
    const markerHtml = `
        <div class="custom-marker" style="background-color: ${lugar.color_marcador}">
            <i class="fas ${lugar.icono}"></i>
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
    lugar.etiquetas.forEach(etiqueta => {
        etiquetasHtml += `
            <span class="badge bg-primary">
                <i class="fas ${etiqueta.icono}"></i> 
                ${etiqueta.nombre}
            </span>
        `;
    });

    return `
        <div class="popup-content">
            <h5>${lugar.nombre}</h5>
            <p>${lugar.descripcion}</p>
            <div class="etiquetas">
                ${etiquetasHtml}
            </div>
            <button id="btnFavorito" class="btn btn-sm ${favoritos.has(lugar.id) ? 'btn-success' : 'btn-outline-danger'}">
                <i class="fas ${favoritos.has(lugar.id) ? 'fa-check' : 'fa-heart'}"></i> ${favoritos.has(lugar.id) ? 'Añadido a favoritos' : 'Añadir a favoritos'}
            </button>
        </div>
    `;
}

// Mostrar detalles en modal
function mostrarDetalles(lugar) {
    currentLugar = lugar;
    const modal = document.getElementById('detallesModal');
    const title = modal.querySelector('.modal-title');
    const body = modal.querySelector('.modal-body');
    
    title.textContent = lugar.nombre;
    body.innerHTML = `
        <p>${lugar.descripcion}</p>
        <div class="etiquetas mb-3">
            ${lugar.etiquetas.map(e => 
                `<span class="badge bg-secondary"><i class="fas ${e.icono}"></i> ${e.nombre}</span>`
            ).join(' ')}
        </div>
        <button id="btnFavorito" class="btn btn-sm ${favoritos.has(lugar.id) ? 'btn-success' : 'btn-outline-danger'}">
            <i class="fas ${favoritos.has(lugar.id) ? 'fa-check' : 'fa-heart'}"></i> ${favoritos.has(lugar.id) ? 'Añadido a favoritos' : 'Añadir a favoritos'}
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
        language: 'es',
        router: L.Routing.osrmv1({
            serviceUrl: 'https://router.project-osrm.org/route/v1'
        }),
        styles: [
            {
                color: '#2196F3', // Azul material design
                weight: 4,        // Línea más gruesa
                opacity: 0.8,     // Algo de transparencia
                lineJoin: 'round' // Uniones redondeadas
            }
        ]
    }).addTo(map);
}

// Toggle favorito
function toggleFavorito(lugarId) {
    if (favoritos.has(lugarId)) {
        favoritos.delete(lugarId);
    } else {
        favoritos.add(lugarId);
    }
    
    // Guardar en localStorage
    localStorage.setItem('favoritos', JSON.stringify(Array.from(favoritos)));
    
    // Actualizar botón
    const btnFavorito = document.getElementById('btnFavorito');
    btnFavorito.classList.toggle('btn-success');
    btnFavorito.classList.toggle('btn-outline-danger');
    btnFavorito.querySelector('i').classList.toggle('fa-check');
    btnFavorito.querySelector('i').classList.toggle('fa-heart');
    btnFavorito.querySelector('span').textContent = favoritos.has(lugarId) ? 'Añadido a favoritos' : 'Añadir a favoritos';
    
    // Si estamos en la vista de favoritos, actualizar los marcadores
    if (activeFilters.favoritos) {
        cargarFavoritos();
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
