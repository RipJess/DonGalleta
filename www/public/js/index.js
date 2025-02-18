document.addEventListener('DOMContentLoaded', function () {
    const mapa = new MapaUbicaciones();
    mapa.obtenerSucursales();

    document.getElementById('boton-entrega').addEventListener('click', function () {
        mapa.buscarPuntoEntrega();
    });

});

function buscaSabor(sabor) {
    window.location.href = `buscar.php?search=${sabor}`;
}

function validaUsuario() {
    if (document.getElementById('usuario-aut') === null) {
        return false;
    }
    return true;
}

function agregarAlCarrito(idProducto_agregar) {
    if (!validaUsuario()) {
        let alerta = document.getElementById('noUsuario');
        alerta.classList.remove('d-none');
        alerta.classList.add('show');
        setTimeout(() => {
            alerta.classList.remove("show");
            alerta.classList.add("d-none");
        }, 3000);
        return;
    }

    fetch('./api/acciones-carrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=agregar&id_producto=' + encodeURIComponent(idProducto_agregar)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let alerta = document.getElementById('exitoCarrito');
                alerta.classList.remove('d-none');
                alerta.classList.add('show');
                setTimeout(() => {
                    alerta.classList.remove("show");
                    alerta.classList.add("d-none");
                }, 3000);
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Errorcito', error));
}

class MapaUbicaciones {
    constructor() {
        this.mapa = L.map('mapa').setView([17.811972882442998, -97.77996156738978], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© Colaboradores de OpenStreetMap'
        }).addTo(this.mapa);
        this.alerta = document.getElementById('mostrarAlerta');
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
                this.pinSucursales(sucursales);
                this.mostrarSucursales(sucursales);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al obtener las sucursales');
            });
    }

    pinSucursales(sucursales) {
        sucursales.forEach(sucursal => {
            const marcador = L.marker([sucursal.latitud, sucursal.longitud])
                .addTo(this.mapa)
                .bindPopup(sucursal.nombre);
        });
    }

    mostrarSucursales(sucursales) {
        let listaUbicaciones = document.getElementById("PuntosEntrega");
        listaUbicaciones.innerHTML = "";

        sucursales.forEach(sucursal => {
            const itemLista = document.createElement('li');
            itemLista.className = 'list-group-item';
            itemLista.style = "cursor: pointer; background-color: transparent; border: none;"
            itemLista.textContent = sucursal.nombre;
            itemLista.setAttribute('data-lat', sucursal.latitud);
            itemLista.setAttribute('data-lng', sucursal.longitud);
            itemLista.setAttribute('data-direccion', sucursal.direccion);
            listaUbicaciones.appendChild(itemLista);
        });

        document.querySelectorAll('.list-group-item').forEach(item => {
            item.addEventListener('click', () => {
                const lat = parseFloat(item.getAttribute('data-lat'));
                const lng = parseFloat(item.getAttribute('data-lng'));

                // Actualizar el mapa
                this.mapa.flyTo([lat, lng], 16, {
                    duration: 1.5,
                    easeLinearity: 0.25
                });
            });
        });
    }

    buscarPuntoEntrega() {
        let busqueda = document.getElementById("busqueda-entrega").value;

        fetch('./api/mapa-ubi.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=buscar-punto-entrega&busqueda=' + encodeURIComponent(busqueda)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.mapa.flyTo([data.sucursal.latitud, data.sucursal.longitud], 16, {
                        duration: 1.5,
                        easeLinearity: 0.25
                    });
                } else {
                    this.alerta.classList.remove('d-none');
                    this.alerta.classList.add('show');
                    setTimeout(() => {
                        this.alerta.classList.remove("show");
                        this.alerta.classList.add("d-none");
                    }, 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al obtener las sucursales');
            });
    }
}