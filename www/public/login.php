<!DOCTYPE html>
<html lang="es-MX">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./css/globals.css">
    <link rel="stylesheet" href="./css/login.css">
    <link rel="icon" type="image/png" sizes="32x32" href="./img/favicon-32x32.png">
    <title>Don Galleta | Login</title>
</head>

<body>
    <div class="container">
        <div class="login-card mx-auto">
            <div class="logo-container">
                <img src="./img/logo.png" alt="Don Galleta Logo" class="img-fluid">
            </div>

            <form method="post" action="./api/iniciar-sesion.php">
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="floatingInput" placeholder="ejemplo@correo.com"
                        name="email">
                    <label for="floatingInput">Correo electrónico</label>
                </div>

                <div class="form-floating">
                    <input type="password" class="form-control" id="floatingPassword" placeholder="••••••••"
                        name="password">
                    <label for="floatingPassword">Contraseña</label>
                    <br>
                    <a href="#" class="forgot-password">¿Olvidate tu contraseña?</a>
                </div>
                <br>

                <div>
                    <?php
                    if (isset($_GET["error"])) {
                        if ($_GET["error"] == 1) {
                            echo '
                                <div class="alert alert-danger" role="alert">
                                    Formulario Vacio
                                </div>
                                ';
                        } else if ($_GET["error"] == 2) {
                            echo '
                                <div class="alert alert-danger text-center" role="alert">
                                    Correo o contraseña incorrectos
                                </div>
                                ';
                        }
                    }
                    ?>
                </div>
                <button type="submit" class="btn btn-brown w-100 py-2">Iniciar sesión</button>
                <div class="text-center mb-3">
                    <br>
                    <!-- <a href="/forgot-password" class="forgot-password">¿Olvidaste tu contraseña?</a> -->
                    <a class="forgot-password" href="registro.php">¿Eres nuevo?</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>