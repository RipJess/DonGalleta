class MapaUbicaciones {
    constructor() {
        this.mapa = L.map('mapa').setView([17.811972882442998, -97.77996156738978], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© Colaboradores de OpenStreetMap'
        }).addTo(this.mapa);
        this.deliveryBox = document.querySelector('.delivery-box p');
    }

    obtenerSucursales() {
        fetch('./api/mapa-ubi.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=obtener_sucursales'
        })
            .then(response => response.json())
            .then(sucursales => {
                this.mostrarSucursales(sucursales);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al obtener las sucursales');
            });
    }

    actualizarDireccionEntrega(nombre, direccion, id) {
        if (this.deliveryBox) {
            let nombreMayus = nombre.toUpperCase();
            this.deliveryBox.innerHTML = `
                <strong data-id="${id}">${nombreMayus}</strong><br>
                ${direccion}
            `;
        }
    }

    mostrarSucursales(sucursales) {
        const listaUbicaciones = document.getElementById('listaUbicaciones');
        listaUbicaciones.innerHTML = '';

        sucursales.forEach(sucursal => {
            const itemLista = document.createElement('li');
            itemLista.className = 'list-group-item list-group-item-action';
            itemLista.textContent = sucursal.nombre;
            itemLista.setAttribute('data-lat', sucursal.latitud);
            itemLista.setAttribute('data-lng', sucursal.longitud);
            itemLista.setAttribute('data-direccion', sucursal.direccion);
            itemLista.setAttribute('data-id', sucursal.id);
            listaUbicaciones.appendChild(itemLista);

            const marcador = L.marker([sucursal.latitud, sucursal.longitud, sucursal.id])
                .addTo(this.mapa)
                .bindPopup(sucursal.nombre);

            // Actualizar dirección al hacer clic en el marcador
            marcador.on('click', () => {
                this.actualizarDireccionEntrega(sucursal.nombre, sucursal.direccion);
            });
        });

        document.querySelectorAll('.list-group-item').forEach(item => {
            item.addEventListener('click', () => {
                const lat = parseFloat(item.getAttribute('data-lat'));
                const lng = parseFloat(item.getAttribute('data-lng'));
                const nombre = item.textContent;
                const direccion = item.getAttribute('data-direccion');
                const id = item.getAttribute('data-id');

                // Actualizar el mapa
                this.mapa.flyTo([lat, lng], 16, {
                    duration: 1.5,
                    easeLinearity: 0.25
                });

                // Actualizar la dirección de entrega
                this.actualizarDireccionEntrega(nombre, direccion, id);
            });
        });
    }
}

const mapa = new MapaUbicaciones();
mapa.obtenerSucursales();