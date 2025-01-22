<!DOCTYPE html>
<html lang="es-MX">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./login.css">
    <link rel="stylesheet" href="./globals.css">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Berkshire+Swash&family=Inter:opsz,wght@14..32,100..900&family=Poppins:ital,wght@0,400;0,500;1,500&display=swap');
    </style>
    <title>Don Galleta | Login</title>
</head>

<body>
    <div class="container">
        <div class="login-card mx-auto">
            <div class="logo-container">
                <img src="img/logo.png" alt="Don Galleta Logo" class="img-fluid">
            </div>
            <form method="post" action="login-db.php">
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="floatingInput" placeholder="ejemplo@correo.com"
                        name="email">
                    <label for="floatingInput">Correo electrónico</label>
                </div>

                <div class="form-floating">
                    <input type="password" class="form-control" id="floatingPassword" placeholder="••••••••"
                        name="password">
                    <label for="floatingPassword">Contraseña</label>
                    <!-- <i class="fas fa-eye password-toggle" id="togglePassword"></i> -->
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
                    <a href="registro.php" class="forgot-password">¿Eres nuevo?</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>