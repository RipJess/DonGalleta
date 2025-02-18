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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css'>
    <link rel="stylesheet" href="./css/globals.css">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="icon" type="image/png" sizes="32x32" href="./img/favicon-32x32.png">
    <title>Don Galleta | Inicio</title>
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
    <section class="container-fluid seccion1">
        <div class="row">
            <div class="col-md-6 parte1">
                <div class="slogan">
                    <p>
                        Galletas que <span style="color: #930606d4;">encantan,</span>
                    </p>
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            Sabores que
                        </div>
                        <div class="col-4 col-sm-2 galleta1">
                            <img src="./img/imagen-1.png">
                        </div>
                        <span class="col-12 col-sm-4" style="color: #930606d4;">inspiran:</span>
                    </div>
                    <div class="row">
                        <div class="col-4 col-sm-2 galleta2">
                            <img src="./img/imagen-2.png" alt="">
                        </div>
                        <span class="col-12 col-sm-10" style="color: #930606d4;">hechas a tu medida!</span>
                    </div>
                </div>
                <div style="text-align: left;">
                    <p class="descripcion">
                        Creamos galletas caseras únicas en una variedad de sabores irresistibles, totalmente
                        personalizables.
                        Disfruta opciones sin gluten, integrales y una selección especial de postres hechos con
                        dedicación
                        para
                        ti.
                    </p>
                </div>
                <div class="cuadro-punto-entrega">
                    <input class="bsq-entrega" id="busqueda-entrega" type="text"
                        placeholder="Buscar un punto de entrega...">
                    <button class="btn-iniciar-sesion" id="boton-entrega" style="color: white;"
                        type="button">Buscar</button>
                </div>
                <div class="entrega">
                    Puntos de entrega populares:
                    <ul class="list-group list-group-horizontal-sm" id="PuntosEntrega">
                    </ul>
                </div>
            </div>
            <div class="col-12 col-md-6 parte2">
                <!-- <img class="img-fluid" src="./img/rectangle-1.png" alt="">
                <img class="img-fluid" src="./img/rectangle-2.png" alt=""> -->
                <div class="main" style="min-height: 70vh;">
                    <div class="swiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide swiper-slide--one">
                            </div>
                            <div class="swiper-slide swiper-slide--two">
                            </div>
                            <div class="swiper-slide swiper-slide--three">
                            </div>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="container-fluid" style="padding: 10px 80px;">
        <!-- <h3>Ubicaciones</h> -->
        <div class="alert alert-warning d-none" id="mostrarAlerta" role="alert">
            No se ha encontrado un punto de entrega.
        </div>

        <div class="row">
            <div class="col-md-3">
            </div>
            <div class="col-md-6">
                <div id="mapa"></div>
            </div>
            <div class="col-md-3"></div>
        </div>
    </section>
    <section style="padding: 40px 80px;">
        <h3>¿Qué sabor tienes en mente?</h3>
        <div class="sabores">
            <?php
            try {
                $sql = "SELECT * FROM Sabores";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $sabores = $stmt->fetchAll(PDO::FETCH_ASSOC);

                for ($i = 0; $i < 6; $i++) {
                    echo "<div class='sabor'>
                            <div class='sabor-foto'>
                                <img class='img-fluid sabor-img' style='cursor:pointer' onclick=buscaSabor('{$sabores[$i]['nombre']}') src='./img/./rectangle-26.png' alt=''>
                            </div>
                            <p class='sabor-nombre'>{$sabores[$i]['nombre']}</p>
                        </div>";
                }

            } catch (PDOException $th) {
                echo $th->getMessage();
            }
            ?>
        </div>
    </section>
    <section class="container-fluid" style="padding: 50px 80px">
        <div class="alert alert-danger d-none" id="noUsuario" role="alert">
            Debes iniciar sesión para agregar productos al carrito!
        </div>
        <div class="alert alert-success d-none" id="exitoCarrito" role="alert">
            Producto agregado al carrito!
        </div>  
        <div class="row">
            <div class="col-12 col-sm-6" style="padding-bottom: 20px;">
                <h3 style="padding: 20px 0;">Galletas recomendadas</h3>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3" style="gap: 30px;">
                    <?php
                    try {
                        $sql = "SELECT * FROM Vista_Productos_Catalogo";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        for ($i = 0; $i < 4; $i++) {
                            echo "<div class='elemento-galleta'>
                                    <img class='img-fluid' src='./img/rectangle-26.png' alt=''>
                                    <p class='nombre-galleta'>{$productos[$i]['nombre_producto']}</p>
                                    <div style='display: flex; flex-direction: row; justify-content: space-between;'>
                                        <div style='color:#808080;' class='text-truncate'>{$productos[$i]['sabores']}</div>
                                        <div style='display: flex; flex-direction: row; gap: 0.4em;'>
                                            <img src='./img/estrella.svg' alt=''>
                                            <div>4,0</div>
                                        </div>
                                    </div>
                                    <div style='display: flex; flex-direction: row; justify-content: center; gap: 1em;'>
                                        <img src='./img/precio.svg' alt=''>
                                        <span>$ {$productos[$i]['precio']}</span>
                                    </div>
                                    <button type='button' id='agregarCarrito' class='btn btn-outline-danger btn-sm btn-accion' onclick='agregarAlCarrito({$productos[$i]['id_producto']})'>Agregar al carrito</button>
                                </div>";
                        }

                    } catch (PDOException $th) {
                        echo $th->getMessage();
                    }
                    ?>
                </div>
            </div>
            <div class="col-12 col-sm-6" style="padding-bottom: 20px;">
                <h3 style="padding: 20px 0;">Galletas nuevas</h3>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3" style="gap: 30px;">
                    <?php
                    try {
                        $sql = "SELECT * FROM Vista_Productos_Catalogo";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        for ($i = 0; $i < 4; $i++) {
                            echo "<div class='elemento-galleta'>
                                <img class='img-fluid' src='./img/rectangle-26.png' alt=''>
                                <p class='nombre-galleta'>{$productos[$i]['nombre_producto']}</p>
                                <div style='display: flex; flex-direction: row; justify-content: space-between;'>
                                    <div style='color:#808080;' class='text-truncate'>{$productos[$i]['sabores']}</div>
                                    <div style='display: flex; flex-direction: row; gap: 0.4em;'>
                                        <img src='./img/estrella.svg' alt=''>
                                        <div>4,0</div>
                                    </div>
                                </div>
                                <div style='display: flex; flex-direction: row; justify-content: center; gap: 1em;'>
                                    <img src='./img/precio.svg' alt=''>
                                    <span>$ {$productos[$i]['precio']}</span>
                                </div>
                                <button type='button' id='agregarCarrito' class='btn btn-outline-danger btn-sm btn-accion' onclick='agregarAlCarrito({$productos[$i]['id_producto']})'>Agregar al carrito</button>
                            </div>";
                        }

                    } catch (PDOException $th) {
                        echo $th->getMessage();
                    }
                    ?>
                </div>
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
                            <button class="nav-link" style="color:#f0d2b8;" data-bs-toggle='modal'
                                data-bs-target='#Formulario'>Contacto</button>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <div class="redes-sociales">
            <a href="https://www.facebook.com/share/1Ba17Uf77Z/?mibextid=wwXIfr">
                <img src="./img/facebook-icon.svg" alt="">
            </a>
            <a href="#">
                <img src="./img/twitter-icon.svg" alt="">
            </a>
            <a href="https://www.instagram.com/dongalletaa?igsh=ZXVtb3JiZThvanQ0">
                <img src="./img/instagram-icon.svg" alt="">
            </a>
        </div>
        <p style="margin: 0; color: #930606d4; text-align: center;">© 2025 Don Galleta. Todos los derechos reservados.
        </p>
    </footer>

    <div class="modal fade" id="Formulario" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Formulario de contacto</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="https://formsubmit.co/castillotadeo513@gmail.com" method="POST">
                        <div class="contact-form-section">
                            <!-- Nombre -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <!-- Correo Electrónico -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <!-- Mensaje -->
                            <div class="mb-3">
                                <label for="message" class="form-label">Mensaje</label>
                                <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                            </div>

                            <!-- Botón de Enviar -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-submit">Enviar Consulta</button>
                            </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger btn-sm btn-accion"
                        data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
    <script src="./js/index.js"></script>
    <script>
        var swiper = new Swiper(".swiper", {
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            coverflowEffect: {
                rotate: 0,
                stretch: 0,
                depth: 100,
                modifier: 3,
                slideShadows: true
            },
            keyboard: {
                enabled: true
            },
            mousewheel: {
                thresholdDelta: 70
            },
            loop: true,
            pagination: {
                el: ".swiper-pagination",
                clickable: true
            },
            breakpoints: {
                640: {
                    slidesPerView: 2
                },
                768: {
                    slidesPerView: 1
                },
                1024: {
                    slidesPerView: 2
                },
                1560: {
                    slidesPerView: 3
                }
            }
        });
    </script>
</body>

</html>