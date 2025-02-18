<?php
session_start();
require_once '../private/databaseController.php';
$pdo = conexion();
?>
<!DOCTYPE html>
<html lang="es-MX">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css'>
    <link rel="stylesheet" href="./css/globals.css">
    <link rel="stylesheet" href="./css/catalogo.css">
    <link rel="icon" type="image/png" sizes="32x32" href="./img/favicon-32x32.png">
    <title>Don Galleta | Catalogo</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg cabecera" style="margin-top: 55px; margin-bottom: 40px;">
        <div class="container-fluid" style="display: flex; justify-content: center;">
            <a href="index.php" class="navbar-brand">
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
            <?php if (isset($_SESSION['id'])): ?>

                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" id="usuario-aut" data-bs-toggle="dropdown" data-bs-display="static"
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

            <?php else: ?>
                <button class="btn-iniciar-sesion">
                    <a href="login.php">Iniciar sesión</a>
                </button>
            <?php endif; ?>
        </div>
    </nav>

    <section style="padding: 5% 2%;">
        <div class="container-fluid" style="padding: 0 10%;">
            <h2 class="texto">Catalogo de productos</h2>
            <div style="padding: 0 30px; width: 20rem;">
                <select class="form-select form-select-sm" id="orden" onchange="mostrarProductos()">
                    <option value="">Ordenar por...</option>
                    <option value="nombre_asc">Nombre (A-Z)</option>
                    <option value="nombre_desc">Nombre (Z-A)</option>
                    <option value="precio_asc">Precio (Menor a Mayor)</option>
                    <option value="precio_desc">Precio (Mayor a Menor)</option>
                    <option value="sabor_asc">Sabor (A-Z)</option>
                    <option value="sabor_desc">Sabor (Z-A)</option>
                </select>
            </div>
            <div class="alert alert-danger d-none" id="noUsuario" role="alert">
                Debes iniciar sesión para agregar productos al carrito!
            </div>
            <div class="alert alert-warning d-none" id="mostrarAlerta" role="alert">
                Producto agregado al carrito!
            </div>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3" id="resultados"
                style="gap: 2rem; justify-content: center; padding: 40px 0;">
            </div>
        </div>
    </section>

    <footer class="pie">
        <div class="footer-contenido">
            <a href="index.php" class="navbar-brand">
                <img class="img-fluid" src="./img/logo-footer.png" alt="logo" width="300">
            </a>
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                        <div class="navbar-nav" style="gap: 20px;">
                            <a class="nav-link" style="color:#f0d2b8;" href="#">Acerca de nosotros</a>
                            <a class="nav-link" style="color:#f0d2b8;" href="#">Entregas</a>
                            <a class="nav-link" style="color:#f0d2b8;" href="#">Blog</a>
                            <a class="nav-link" style="color:#f0d2b8;" href="#">Colabora con nosotros</a>
                            <a class="nav-link" style="color:#f0d2b8;" href="#">Contacto</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <div class="redes-sociales">
            <a href="#">
                <img src="./img/facebook-icon.svg" alt="">
            </a>
            <a href="#">
                <img src="./img/twitter-icon.svg" alt="">
            </a>
            <a href="">
                <img src="./img/instagram-icon.svg" alt="">
            </a>
        </div>
        <p style="margin: 0; color: #930606d4; text-align: center;">© 2025 Don Galleta. Todos los derechos reservados.
        </p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/buscar.js"></script>
</body>

</html>