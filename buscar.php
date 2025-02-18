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
    <link rel="stylesheet" href="./css/globals.css">
    <link rel="stylesheet" href="./css/buscar.css">
    <link rel="icon" type="image/png" sizes="32x32" href="./img/favicon-32x32.png">
    <title>Don Galleta | Buscar</title>
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

    <div class="container-fluid" style="padding: 0 10%;">
        <p class="texto">Resultados para: "<?php echo htmlspecialchars($_GET['search']); ?>" </p>
        <div style="padding: 10px 30px 30px; width: 40rem; display: flex; justify-content: space-between;">
            <input type="text" class="form-control" id="busqueda"
                value="<?php echo htmlspecialchars($_GET['search']); ?>"
                placeholder="<?php echo htmlspecialchars($_GET['search']); ?>" oninput="mostrarProductos()">
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
        <div class="alert alert-success d-none" id="mostrarAlerta" role="alert">
            Producto agregado al carrito!
        </div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3" id="resultados"
            style="gap: 2rem; justify-content: center;">
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/buscar.js"></script>
</body>
<script>

</script>

</html>