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
            <?php if (isset($_SESSION['id'])): ?>
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
            <?php else: ?>
                <button class="btn-iniciar-sesion">
                    <a href="login.php">Iniciar sesión</a>
                </button>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container-fluid" style="padding: 0 10%;">
        <p class="texto">Resultados para: "<?php echo htmlspecialchars($_GET['search']); ?>" </p>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3" style="gap: 2rem; justify-content: center;">
            <?php
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $query = trim($_GET['search']);
                try {
                    $sql = "SELECT p.*, s.nombre AS sabor
                                        FROM Productos p
                                        JOIN Sabores s ON p.id_sabor = s.id_sabor
                                        WHERE p.nombre LIKE :query OR s.nombre LIKE :query";

                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':query' => '%' . $query . '%']);
                    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($productos) > 0) {
                        foreach ($productos as $producto) {
                            echo "
                            <div class='casilla'>
                                <img class='img-fluid' style='border-radius: 25px;' width='200' src='img/rectangle-26.png'>
                                <div class='descripcion'>
                                    <p class='nombre-galleta'>{$producto['nombre']}</p>
                                    <p class='sabor'>{$producto['sabor']}</p>
                                    <div class='precio'>
                                        <img src='img/precio.svg'>
                                        <p class='nombre-galleta' style='font-size: 16px; margin-bottom: 0px;'>{$producto['precio']}</p>
                                    </div>
                                    <br>
                                    <form action='agregar-al-carrito.php' method='post'>
                                        <input type='hidden' name='id_producto' value='{$producto['id_producto']}'>
                                        <input type='hidden' name='action' value='agregar'>
                                        <input type='hidden' name='busqueda' value='{$_GET['search']}'>
                                        <button type='submit' class='btn btn-outline-danger btn-sm btn-accion'>Agregar al carrito</button>
                                    </form>
                                </div>
                            </div>
                            ";
                        }
                    } else {
                        echo "<p>No se encontraron productos relacionados con tu búsqueda.</p>";
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            } else {
                echo "<p>Por favor, ingresa un término de búsqueda.</p>";
            }
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>