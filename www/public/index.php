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
    <link rel="stylesheet" href="./css/index.css">
    <link rel="icon" type="image/png" sizes="32x32" href="./img/favicon-32x32.png">
    <title>Don Galleta | Inicio</title>
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
                    <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                        data-bs-display="static" aria-expanded="false">
                        <?php echo htmlspecialchars($_SESSION["user"]); ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-lg-end" style="background-color:transparent;">
                        <?php if ($_SESSION['rol'] === "administrador"): ?>
                        <li><button class="dropdown-item" type="buttom"><a href="admin.php" style="color: black;">Administrar</a></button></li>
                        <?php endif; ?>
                        <li><button class="dropdown-item" type="button">Action</button></li>
                        <li><button class="dropdown-item" type="button">Another action</button></li>
                        <hr class="dropdown-divider">
                        <li><button class="dropdown-item" type="button"><a href="./api/cerrar-sesion.php" style="color: black;">Cerrar sesión</a></button></li>
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
                <form action="#" method="get" class="cuadro-punto-entrega">
                    <input class="bsq-entrega" type="text" name="search" placeholder="Buscar un punto de entrega...">
                    <button class="btn-iniciar-sesion" style="color: white;" type="submit">Buscar</button>
                </form>
                <div class="entrega">
                    Puntos de entrega populares:
                    <!-- <nav class="navbar navbar-expand-lg">
                            <div class="container-fluid">
                                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false"
                                    aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                                    <div class="navbar-nav">
                                        <a class="nav-link entrega1" href="#">Colonia Roma</a>
                                        <a class="nav-link entrega2" href="#">Colonia Condesa</a>
                                        <a class="nav-link entrega1" href="#">Colonia Polanco</a>
                                        <a class="nav-link entrega2" href="#">Colonia Del Valle</a>
                                        <a class="nav-link entrega1" href="#">Colonia Narvarte</a>
                                    </div>
                                </div>
                            </div>
                        </nav> -->
                    <ul class="list-group list-group-horizontal-sm">
                        <li class="list-group-item" style="background-color: transparent; border: none;"><a
                                style="color:#808080;" href="#">colonia</a></li>
                        <li class="list-group-item" style="background-color: transparent; border: none;"><a
                                style="color:#930606d4;" href="#">colonia</a></li>
                        <li class="list-group-item" style="background-color: transparent; border: none;"><a
                                style="color:#808080;" href="#">colonia</a></li>
                        <li class="list-group-item" style="background-color: transparent; border: none;"><a
                                style="color:#930606d4;" href="#">colonia</a></li>
                        <li class="list-group-item" style="background-color: transparent; border: none;"><a
                                style="color:#808080;" href="#">colonia</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-12 col-md-6 parte2">
                <!-- <img class="img-fluid" src="./img/rectangle-1.png" alt="">
                <img class="img-fluid" src="./img/rectangle-2.png" alt=""> -->
                <div class="main">
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
    <section style="padding: 40px 80px;">
        <h3>¿Qué sabor tienes en mente?</h3>
        <div class="sabores">
            <div class="sabor">
                <div class="sabor-foto">
                    <img class="img-fluid sabor-img" src="./img/./rectangle-26.png" alt="">
                </div>
                <p class="sabor-nombre">Sabor</p>
            </div>
            <div class="sabor">
                <div class="sabor-foto">
                    <img class="img-fluid sabor-img" src="./img/./rectangle-26.png" alt="">
                </div>
                <p class="sabor-nombre">Sabor</p>
            </div>
            <div class="sabor">
                <div class="sabor-foto">
                    <img class="img-fluid sabor-img" src="./img/./rectangle-26.png" alt="">
                </div>
                <p class="sabor-nombre">Sabor</p>
            </div>
            <div class="sabor">
                <div class="sabor-foto">
                    <img class="img-fluid sabor-img" src="./img/./rectangle-26.png" alt="">
                </div>
                <p class="sabor-nombre">Sabor</p>
            </div>
            <div class="sabor">
                <div class="sabor-foto">
                    <img class="img-fluid sabor-img" src="./img/./rectangle-26.png" alt="">
                </div>
                <p class="sabor-nombre">Sabor</p>
            </div>
            <div class="sabor">
                <div class="sabor-foto">
                    <img class="img-fluid sabor-img" src="./img/./rectangle-26.png" alt="">
                </div>
                <p class="sabor-nombre">Sabor</p>
            </div>

        </div>
    </section>
    <section class="container-fluid row" style="padding: 50px 80px;">
        <div class="col-12 col-sm-6" style="justify-content: center; padding-bottom: 20px;">
            <h3 style="padding: 20px 0;">Galletas recomendadas</h3>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3" style="gap: 30px;">
                <div class="elemento-galleta">
                    <img class="img-fluid" src="./img/rectangle-26.png" alt="">
                    <p class="nombre-galleta">
                        Nombre de la galleta
                    </p>
                    <div style="display: flex; flex-direction: row; justify-content: space-between;">
                        <div style="color:#808080;">Sabor</div>
                        <div style="display: flex; flex-direction: row; gap: 0.4em;">
                            <img src="./img/estrella.svg" alt="">
                            <div>4,0</div>
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: row; justify-content: center; gap: 1em;">
                        <img src="./img/precio.svg" alt="">
                        <span>$ 00.00</span>
                    </div>
                </div>
                <div class="elemento-galleta">
                    <img class="img-fluid" src="./img/rectangle-26.png" alt="">
                    <p class="nombre-galleta">
                        Nombre de la galleta
                    </p>
                    <div style="display: flex; flex-direction: row; justify-content: space-between;">
                        <div style="color:#808080;">Sabor</div>
                        <div style="display: flex; flex-direction: row; gap: 0.4em;">
                            <img src="./img/estrella.svg" alt="">
                            <div>4,0</div>
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: row; justify-content: center; gap: 1em;">
                        <img src="./img/precio.svg" alt="">
                        <span>$ 00.00</span>
                    </div>
                </div>
                <div class="elemento-galleta">
                    <img class="img-fluid" src="./img/rectangle-26.png" alt="">
                    <p class="nombre-galleta">Nombre de la galleta</p>
                    <div style="display: flex; flex-direction: row; justify-content: space-between;">
                        <div style="color:#808080;">Sabor</div>
                        <div style="display: flex; flex-direction: row; gap: 0.4em;">
                            <img src="./img/estrella.svg" alt="">
                            <div>4,0</div>
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: row; justify-content: center; gap: 1em;">
                        <img src="./img/precio.svg" alt="">
                        <span>$ 00.00</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6" style="justify-content: center; padding-bottom: 20px;">
            <h3 style="padding: 20px 0;">Galletas nuevas</h3>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3" style="gap: 30px;">
                <div class="elemento-galleta">
                    <img class="img-fluid" src="./img/rectangle-26.png" alt="">
                    <p class="nombre-galleta">
                        Nombre de la galleta
                    </p>
                    <div style="display: flex; flex-direction: row; justify-content: space-between;">
                        <div style="color:#808080;">Sabor</div>
                        <div style="display: flex; flex-direction: row; gap: 0.4em;">
                            <img src="./img/estrella.svg" alt="">
                            <div>4,0</div>
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: row; justify-content: center; gap: 1em;">
                        <img src="./img/precio.svg" alt="">
                        <span>$ 00.00</span>
                    </div>
                </div>
                <div class="elemento-galleta">
                    <img class="img-fluid" src="./img/rectangle-26.png" alt="">
                    <p class="nombre-galleta">
                        Nombre de la galleta
                    </p>
                    <div style="display: flex; flex-direction: row; justify-content: space-between;">
                        <div style="color:#808080;">Sabor</div>
                        <div style="display: flex; flex-direction: row; gap: 0.4em;">
                            <img src="./img/estrella.svg" alt="">
                            <div>4,0</div>
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: row; justify-content: center; gap: 1em;">
                        <img src="./img/precio.svg" alt="">
                        <span>$ 00.00</span>
                    </div>
                </div>
                <div class="elemento-galleta">
                    <img class="img-fluid" src="./img/rectangle-26.png" alt="">
                    <p class="nombre-galleta">Nombre de la galleta</p>
                    <div style="display: flex; flex-direction: row; justify-content: space-between;">
                        <div style="color:#808080;">Sabor</div>
                        <div style="display: flex; flex-direction: row; gap: 0.4em;">
                            <img src="./img/estrella.svg" alt="">
                            <div>4,0</div>
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: row; justify-content: center; gap: 1em;">
                        <img src="./img/precio.svg" alt="">
                        <span>$ 00.00</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="pie">
        <div class="footer-contenido">
            <a href="index.html " class="navbar-brand">
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
    <script src='https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js'></script>
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