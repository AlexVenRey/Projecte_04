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
    map = L.map('mapa').setView([41.390205, 2.154007], 13); // Coordenadas iniciales

    // Cargar el mapa base
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    // Evento para capturar clics en el mapa
    map.on('click', function (e) {
        const { lat, lng } = e.latlng;

        // Mostrar el modal y rellenar los campos de latitud y longitud
        document.getElementById('latitud').value = lat.toFixed(6);
        document.getElementById('longitud').value = lng.toFixed(6);
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
    const Δλ = (lon1 - lon2) * Math.PI / 180;

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
        const response = await fetch('/cliente/mis-favoritos');
        if (!response.ok) {
            throw new Error('Error al cargar favoritos');
        }
        const lugares = await response.json();

        // Asegurarse de que cada lugar tenga el estado `es_favorito` como `true`
        lugares.forEach(lugar => lugar.es_favorito = true);

        mostrarLugares(lugares);
    } catch (error) {
        console.error('Error cargando favoritos:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al cargar tus favoritos'
        });
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
        icon: createCustomMarker(lugar),
        lugarId: lugar.id, // Identificador único del lugar
        lugarData: lugar,  // Datos del lugar para actualizar el popup
        color: lugar.color_marcador // Color del marcador
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

    // Determinar el texto y estilo del botón según el estado `es_favorito`
    const esFavorito = lugar.es_favorito; // Este dato vendrá del servidor
    const botonFavoritoTexto = esFavorito ? 'Quitar de favoritos' : 'Añadir a favoritos';
    const botonFavoritoClase = esFavorito ? 'btn-danger' : 'btn-outline-success';
    const botonFavoritoIcono = esFavorito ? 'fa-times' : 'fa-heart';

    return `
        <div class="popup-content">
            <h5>${lugar.nombre}</h5>
            <p>${lugar.descripcion}</p>
            <div class="etiquetas">
                ${etiquetasHtml}
            </div>
            <button onclick="toggleFavorito(${lugar.id})" class="btn btn-sm ${botonFavoritoClase}">
                <i class="fas ${botonFavoritoIcono}"></i> ${botonFavoritoTexto}
            </button>
            <button type="button" class="btn btn-primary" onclick="mostrarRuta(${lugar.id})">
                <i class="fas fa-route"></i> Ver ruta
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
function mostrarRuta(lugarId) {
    // Buscar el lugar por su ID
    const lugar = markers.find(marker => marker.options.lugarId === lugarId)?.options.lugarData;

    if (!lugar) {
        alert('No se encontró el lugar seleccionado.');
        return;
    }

    if (!currentPosition) {
        alert('Por favor, permite el acceso a tu ubicación para ver la ruta.');
        return;
    }

    // Eliminar el control de ruta anterior si existe
    if (routingControl) {
        map.removeControl(routingControl);
    }

    // Crear un nuevo control de ruta con las indicaciones
    routingControl = L.Routing.control({
        waypoints: [
            L.latLng(currentPosition[0], currentPosition[1]), // Ubicación actual
            L.latLng(lugar.latitud, lugar.longitud)           // Ubicación del lugar
        ],
        routeWhileDragging: false,
        showAlternatives: false,
        fitSelectedRoute: true,
        language: 'es', // Configurar el idioma a español
        router: L.Routing.mapbox('pk.eyJ1IjoiZXZhcmlzdG82NyIsImEiOiJjbThuZm1xYjEwMDlxMnZzYWl4NG01dnU1In0.P3Jq6ts8g-gh3HjD611hcg', {
            profile: 'mapbox/walking'
        }),
        createMarker: function (i, waypoint, n) {
            // Personalizar los marcadores de inicio y fin
            if (i === 0) {
                return L.marker(waypoint.latLng, {
                    icon: L.divIcon({
                        html: '<i class="fas fa-user-circle fa-2x" style="color: #2196F3;"></i>',
                        className: 'user-location-marker',
                        iconSize: [25, 25],
                        iconAnchor: [12, 12]
                    })
                });
            } else if (i === n - 1) {
                return L.marker(waypoint.latLng, {
                    icon: L.divIcon({
                        html: '<i class="fas fa-map-marker-alt fa-2x" style="color: #FF0000;"></i>',
                        className: 'destination-marker',
                        iconSize: [25, 25],
                        iconAnchor: [12, 12]
                    })
                });
            }
        },
        lineOptions: {
            styles: [
                {
                    color: '#2196F3', // Azul material design
                    weight: 4,        // Línea más gruesa
                    opacity: 0.8,     // Algo de transparencia
                    lineJoin: 'round' // Uniones redondeadas
                }
            ]
        }
    }).addTo(map);

    // Interceptar las instrucciones y traducirlas
    routingControl.on('routesfound', function (e) {
        const routes = e.routes;
        const instructions = routes[0].instructions;

        instructions.forEach(instruction => {
            instruction.text = traducirTexto(instruction.text);
        });
    });
}

// Función para traducir texto (puedes personalizarla)
function traducirTexto(texto) {
    const traducciones = {
        'Head': 'Dirígete',
        'Turn right': 'Gira a la derecha',
        'Turn left': 'Gira a la izquierda',
        'Continue': 'Continúa',
        'Take the roundabout': 'Toma la rotonda',
        'onto': 'hacia',
        'Keep right': 'Mantente a la derecha',
        'Keep left': 'Mantente a la izquierda',
        'You have arrived at your destination': 'Has llegado a tu destino',
        'on the right': 'a la derecha',
        'on the left': 'a la izquierda',
        'west on': 'hacia el oeste en',
        'east on': 'hacia el este en',
        'north on': 'hacia el norte en',
        'south on': 'hacia el sur en',
        'to stay on': 'para continuar en',
        'Make a slight right': 'Gira levemente a la derecha',
        'Make a slight left': 'Gira levemente a la izquierda',
        'towards': 'hacia',
        'Stay on': 'Permanece en',
        'Merge onto': 'Incorpórate a',
        'Exit': 'Sal de',
        'at the roundabout': 'en la rotonda',
        'Take the exit': 'Toma la salida',
        'meters': 'metros',
        'kilometers': 'kilómetros'
    };

    for (const [ingles, espanol] of Object.entries(traducciones)) {
        texto = texto.replace(new RegExp(ingles, 'g'), espanol);
    }

    return texto;
}

// Toggle favorito
async function toggleFavorito(lugarId) {
    try {
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
            // Actualizar el marcador en el mapa
            actualizarMarcadorFavorito(lugarId, data.esFavorito);

            // Actualizar el contenido del popup
            actualizarPopup(lugarId, data.esFavorito);

            // Actualizar el contenido del modal
            actualizarModalContent(lugarId, data.esFavorito);

            Swal.fire({
                icon: 'success',
                title: data.esFavorito ? '¡Añadido!' : 'Eliminado',
                text: data.message,
                timer: 1500,
                showConfirmButton: false
            });
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

// Actualizar el botón de favorito en el modal
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
        }
    }
}

// Actualizar el marcador en el mapa para reflejar el estado de favorito
function actualizarMarcadorFavorito(lugarId, esFavorito) {
    const marker = markers.find(m => m.options.lugarId === lugarId);
    if (marker) {
        const iconHtml = `
            <div class="custom-marker" style="background-color: ${marker.options.color}">
                <i class="fas ${esFavorito ? 'fa-heart' : 'fa-map-marker-alt'}"></i>
            </div>
        `;
        marker.setIcon(L.divIcon({
            html: iconHtml,
            className: 'custom-marker-container',
            iconSize: [30, 30],
            iconAnchor: [15, 30],
            popupAnchor: [0, -30]
        }));
    }
}

function actualizarPopup(lugarId, esFavorito) {
    const marker = markers.find(m => m.options.lugarId === lugarId);
    if (marker) {
        const popupContent = createPopupContent({
            ...marker.options.lugarData,
            es_favorito: esFavorito
        });
        marker.getPopup().setContent(popupContent);
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

document.addEventListener("DOMContentLoaded", function () {
    initMap();

    // Guardar el punto al hacer clic en "Guardar Punto"
    document.getElementById('addPointForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validar el formulario
        const formData = new FormData(this);
        const errors = [];
        
        if (!formData.get('nombre').trim()) {
            errors.push('El nombre es obligatorio');
        }
        if (!formData.get('descripcion').trim()) {
            errors.push('La descripción es obligatoria');
        }
        if (!formData.get('latitud') || !formData.get('longitud')) {
            errors.push('Las coordenadas son obligatorias');
        }

        if (errors.length > 0) {
            alert(errors.join('\n'));
            return;
        }

        // Enviar el formulario
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Añadir el marcador al mapa
                const marcador = L.marker([data.latitud, data.longitud], {
                    icon: L.divIcon({
                        className: 'custom-marker',
                        html: `<div style="background-color: ${data.color_marcador}; width: 20px; height: 20px; border-radius: 50%;"></div>`,
                        iconSize: [20, 20]
                    })
                }).addTo(map);

                // Añadir popup al marcador
                marcador.bindPopup(`
                    <strong>${data.nombre}</strong><br>
                    ${data.descripcion}
                `);

                // Cerrar el modal y limpiar el formulario
                const addPointModal = new bootstrap.Modal(document.getElementById('addPointModal'));
                addPointModal.hide();
                this.reset();
            } else {
                alert('Error al guardar el marcador');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al guardar el marcador');
        });
    });
});