<?php
session_start();
require_once "../private/databaseController.php";
$pdo = conexion();

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
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
            <a href="index.php " class="navbar-brand">
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
                    <button type="button" class="btn-vaciar mt-3 mb-2" onclick="vaciarCarrito()">Vaciar carrito</button>
                    <button type="button" id="realizarPago" class="btn-checkout mt-3 mb-2"
                        onclick="validarPago()">Realizar pago</button>
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

    <div class="modal fade" id="realizarCompra" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Realizar compra</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="payment-container">
                        <h2 class="mb-3">Escoge tu método de pago</h2>
                        <div id="paypal-button-container"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger btn-sm btn-accion"
                        data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
    <script
        src="https://www.paypal.com/sdk/js?client-id=AadDx0jK0TElff2zyNBXSpvKdl0dIKPPxz44C-F960lcA65rPUiqUBnnbc9CGs360o9Su3DEtWVZdSGH&currency=MXN"
        data-sdk-integration-source="developer-studio"></script>
    <script src='./js/carrito.js'></script>
    <script src='./js/mapa.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            paypal.Buttons({
                style: {
                    color: 'blue',
                    shape: 'pill',
                    label: 'pay'
                },
                createOrder: function (data, actions) {
                    return fetch('./api/acciones-carrito.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'action=precioTotal'
                    })
                        .then(response => response.json())
                        .then(data => {
                            return actions.order.create({
                                purchase_units: [{
                                    amount: {
                                        value: data.total
                                    }
                                }]
                            });
                        })
                        .catch(error => {
                            console.error("Error obteniendo el total:", error);
                            alert("Error al obtener el total del carrito.");
                        });
                },
                onApprove: function (data, actions) {
                    return actions.order.capture().then(function (details) {
                        let sucursal = parseInt(document.querySelector(".delivery-box p strong").dataset.id);

                        // Enviar datos a PHP para guardarlos en la base de datos
                        fetch('./api/acciones-paypal.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                orderID: data.orderID,
                                details: details,
                                sucursal: sucursal
                            })
                            // 'orderID='+encodeURIComponent(data.orderID)+'&details='+encodeURIComponent(JSON.stringify(details))
                        })
                            .then(response => response.json())
                            .then(data => {
                                // console.log("Respuesta cruda del servidor:", data);
                                // return JSON.parse(data);
                                if (data.success) {
                                    alert("✔️ Pago completado correctamente.");
                                    window.location.href = "ticket.php";
                                    // window.location.href = "carrito.php";
                                } else {
                                    alert("Error al guardar el pago: " + data.message);
                                }
                            })
                            .catch(error => console.error("Error de cagada:", error));
                    });
                },
                onError: function (err) {
                    console.error('Error en PayPal:', err);
                    alert("❌ Error en el pago. Intenta de nuevo.");
                }
            }).render('#paypal-button-container');
        });
    </script>
</body>

</html>