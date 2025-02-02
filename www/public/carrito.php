<?php
session_start();
require_once "../private/databaseController.php";
$pdo = conexion();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es-MX">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/globals.css">
    <link rel="stylesheet" href="./css/carrito.css">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
    <title>Don Galleta | Carrito de compras</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg cabecera" style="margin-top: 55px; margin-bottom: 40px;">
        <div class="container-fluid" style="display: flex; justify-content: center;">
            <a href="index.html " class="navbar-brand">
                <img class="img-fluid" src="./img/logo.png" alt="logo" width="550">
            </a>
        </div>
        <div class="container-fluid" style="display: flex; justify-content: center; gap: 1rem;">
            <form action="buscar.php" method="get" class="busqueda">
                <input type="text" name="search" placeholder="Buscar...">
                <button type="submit">
                    <img src="./img/busqueda.svg" alt="">
                </button>
            </form>
            <button>
                <a href="catalogo.php">
                    <img src="./img/productos.svg" alt="catalogo">
                </a>
            </button>
            <button>
                <a href="carrito.php">
                    <img src="./img/carrito.svg" alt="carrito">
                </a>
            </button>
            <div class="btn-group">
                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static"
                    aria-expanded="false">
                    <?php echo htmlspecialchars($_SESSION["user"]); ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-lg-end" style="background-color:transparent;">
                    <?php if ($_SESSION['rol'] === "administrador"): ?>
                        <li><button class="dropdown-item" type="buttom"><a href="admin.php"
                                    style="color: black;">Administrar</a></button></li>
                    <?php endif; ?>
                    <li><button class="dropdown-item" type="button">Action</button></li>
                    <li><button class="dropdown-item" type="button">Another action</button></li>
                    <hr class="dropdown-divider">
                    <li><button class="dropdown-item" type="button"><a href="./api/cerrar-sesion.php"
                                style="color: black;">Cerrar sesión</a></button></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-container">
        <div class="cart-section">
            <div class="cart-container">
                <div class="d-flex justify-content-between mb-4">
                    <h3>Carrito</h3>
                    <span id="num-items">No. Items: 0</span>
                </div>
                <div id="productos-carrito">
                    <!-- Los productos se cargarán dinámicamente aquí -->
                </div>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <h4>Total</h4>
                    <h4 id="total-carrito">$0.00</h4>
                </div>
                <div class="frame">
                    <button class="btn-vaciar mt-3 mb-2" onclick="vaciarCarrito()">Vaciar carrito</button>
                    <button class="btn-checkout mt-3 mb-2" onclick="realizarPago()">Realizar pago</button>
                </div>
            </div>
            <div class="delivery-box">
                <h4 class="punto-entrega mb-3">Punto de entrega</h4>
                <p class="mb-0">
                    sin punto de entrega seleccionado
                </p>
            </div>

            <!-- Sección de ubicaciones que ocupa todo el ancho -->
            <div class="ubicaciones-section w-100">
                <h3 class="ubi mb-4">Ubicaciones</h3>

                <!-- Contenedor para el mapa y lista de ubicaciones -->
                <div class="ubicaciones-container">
                    <div class="row">
                        <div class="col-md-4">
                            <div class=" ard">
                                <div class="list-group list-group-flush" id="listaUbicaciones">
                                    <!-- Los elementos de la lista se generarán dinámicamente aquí -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div id="mapa"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
    <script>
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

            actualizarDireccionEntrega(nombre, direccion) {
                if (this.deliveryBox) {
                    let nombreMayus = nombre.toUpperCase();
                    this.deliveryBox.innerHTML = `
                        <strong>${nombreMayus}</strong><br>
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
                    listaUbicaciones.appendChild(itemLista);

                    const marcador = L.marker([sucursal.latitud, sucursal.longitud])
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

                        // Actualizar el mapa
                        this.mapa.flyTo([lat, lng], 16, {
                            duration: 1.5,
                            easeLinearity: 0.25
                        });

                        // Actualizar la dirección de entrega
                        this.actualizarDireccionEntrega(nombre, direccion);
                    });
                });
            }
        }

        const mapa = new MapaUbicaciones();
        mapa.obtenerSucursales();
    </script>
    <script>
        // Plantilla para un producto en el carrito
        function productoTemplate(producto) {
            return `
                <div class="product-row" data-id="${producto.id_producto}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5>${producto.nombre}</h5>
                            <p class="text-muted">$${producto.precio}</p>
                        </div>
                        <div class="quantity-control">
                            <button class="quantity-btn" onclick="decrementarCantidad(${producto.id_producto})">-</button>
                            <input type="text" value="${producto.cantidad}" class="quantity-input" readonly>
                            <button class="quantity-btn" onclick="incrementarCantidad(${producto.id_producto})">+</button>
                        </div>
                    </div>
                </div>
            `;
        }


        function cargarCarrito() {
            fetch('./api/acciones-carrito.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=obtener'
            })
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('productos-carrito');
                    container.innerHTML = '';
                    data.productos.forEach(producto => {
                        container.innerHTML += productoTemplate(producto);
                    });
                    document.getElementById('total-carrito').textContent = `$${data.total.toFixed(2)}`;
                    document.getElementById('num-items').textContent = `No. Items: ${data.num_items}`;
                });
        }

        function actualizarCantidad(id_producto, cantidad) {
            fetch('./api/acciones-carrito.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=actualizar&id_producto=${id_producto}&cantidad=${cantidad}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        cargarCarrito();
                    } else {
                        alert(data.message);
                    }
                });
        }

        function vaciarCarrito() {
            if (confirm('¿Está seguro que desea vaciar el carrito?')) {
                fetch('./api/acciones-carrito.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=vaciar'
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            cargarCarrito(); // Recarga el carrito vacío
                        } else {
                            alert('Error al vaciar el carrito');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al procesar la solicitud');
                    });
            }
        }

        function incrementarCantidad(id_producto) {
            const input = document.querySelector(`.product-row[data-id="${id_producto}"] .quantity-input`);
            const nuevaCantidad = parseInt(input.value) + 1;
            actualizarCantidad(id_producto, nuevaCantidad);
        }

        function decrementarCantidad(id_producto) {
            const input = document.querySelector(`.product-row[data-id="${id_producto}"] .quantity-input`);
            const nuevaCantidad = parseInt(input.value) - 1;
            actualizarCantidad(id_producto, nuevaCantidad);
        }

        function realizarPago() {
            alert('Redirigiendo al proceso de pago...');
        }

        // Cargar el carrito al iniciar la página
        document.addEventListener('DOMContentLoaded', cargarCarrito);
    </script>
</body>

</html>