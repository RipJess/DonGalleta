<!DOCTYPE html>
<html lang="es-MX">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./carrito.css">
    <link rel="stylesheet" href="./globals.css">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Berkshire+Swash&family=Inter:opsz,wght@14..32,100..900&family=Poppins:ital,wght@0,400;0,500;1,500&display=swap');
    </style>
</head

<body>
    <div class="frame-18">
        <div class="frame-21">
            <img class="img-fluid" src="img/logo.png" />
        </div>
        <div class="frame-19">
            <form class="recuadro_busqueda" method="get">
                <input type="text" id="buscar" placeholder="Buscar..." />
            </form>
            <div class="sign-in">
                <div class="btn-iniciar-sesion">
                    <a class="boton" href="login.php"> Iniciar sesión </a>
                </div>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="cart-section">
            <div class="cart-container">
                <div class="d-flex justify-content-between mb-4">
                    <h3>Carrito</h3>
                    <span>No. Items</span>
                </div>
                <div class="product-row">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Nombre de la galleta</h5>
                            <p class="text-muted">Precio</p>
                        </div>
                        <div class="quantity-control">
                            <button class="quantity-btn">-</button>
                            <input type="text" value="1" class="quantity-input" readonly>
                            <button class="quantity-btn">+</button>
                        </div>
                    </div>
                </div>
                <div class="product-row">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5>Nombre de la galleta</h5>
                            <p class="text-muted">Precio</p>
                        </div>
                        <div class="quantity-control">
                            <button class="quantity-btn">-</button>
                            <input type="text" value="1" class="quantity-input" readonly>
                            <button class="quantity-btn">+</button>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <h4>Total</h4>
                    <h4>$932.00</h4>
                </div>
                <button class="btn-checkout mt-4">Realizar pago</button>
            </div>
            <div class="delivery-box">
                <h4 class="mb-3">Punto de entrega</h4>
                <p class="mb-0">
                    Av. Doctor Modesto Seara Vázquez #1, Acatlima, 69000 Heroica Cdad. de Huajuapan de León, Oax.                
                </p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>