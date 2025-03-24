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
            option.innerHTML = `<i class="fas ${etiqueta.icono}"></i> ${etiqueta.nombre}`;
            select.appendChild(option);
        });
    } catch (error) {
        console.error('Error cargando etiquetas:', error);
    }
}

// Función para calcular la distancia entre dos puntos usando la fórmula de Haversine
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
        const response = await fetch('/cliente/lugares');
        const lugares = await response.json();
        
        // Filtrar lugares por distancia
        const lugaresCercanos = lugares.filter(lugar => {
            const distanciaEnMetros = calcularDistancia(
                currentPosition[0],
                currentPosition[1],
                lugar.latitud,
                lugar.longitud
            );
            return distanciaEnMetros <= distancia;
        });

        // Limpiar mapa y mostrar lugares cercanos
        limpiarMapa();
        mostrarLugares(lugaresCercanos);

        // Si no hay lugares cercanos, mostrar mensaje
        if (lugaresCercanos.length === 0) {
            alert(`No se encontraron lugares en un radio de ${distancia} metros.`);
        } else {
            // Ajustar el zoom del mapa para mostrar todos los lugares encontrados
            const bounds = L.latLngBounds([currentPosition]);
            lugaresCercanos.forEach(lugar => {
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
        
        // Actualizar UI
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
        
        // Actualizar UI
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

        // Actualizar UI
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
        const response = await fetch('/cliente/lugares');
        const lugares = await response.json();
        const lugaresConFavoritos = lugares.filter(lugar => favoritos.has(lugar.id));
        mostrarLugares(lugaresConFavoritos);
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
        router: L.Routing.mapbox('pk.eyJ1IjoiZXZhcmlzdG82NyIsImEiOiJjbThuZm1xYjEwMDlxMnZzYWl4NG01dnU1In0.P3Jq6ts8g-gh3HjD611hcg', {
            profile: 'mapbox/walking'
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
    
    // Actualizar UI
    const btnFavorito = document.getElementById('btnFavorito');
    if (btnFavorito) {
        btnFavorito.classList.toggle('btn-success');
        btnFavorito.classList.toggle('btn-outline-danger');
        const icon = btnFavorito.querySelector('i');
        if (icon) {
            icon.classList.toggle('fa-check');
            icon.classList.toggle('fa-heart');
        }
        const span = btnFavorito.querySelector('span');
        if (span) {
            span.textContent = favoritos.has(lugarId) ? 'Añadido a favoritos' : 'Añadir a favoritos';
        }
    }
    
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
