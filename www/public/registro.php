<!DOCTYPE html>
<html lang="es-MX">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/globals.css">
    <link rel="stylesheet" href="./css/login.css">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
    <title>Don Galleta | Registro</title>
</head>

<body>
    <div class="container">
        <div class="login-card mx-auto">
            <div class="logo-container">
                <img src="img/logo.png" alt="Don Galleta Logo" class="img-fluid">
            </div>

            <!-- Formulario con método POST -->
            <form method="POST" action="./api/registrar.php">

                <!-- Campo Usuario -->
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" placeholder="ejemplo@correo.com"
                        name="nombre">
                    <label for="floatingInput">Ingrese un nombre de usuario</label>
                </div>
                <?php
                if (isset($_GET['error'])) {
                    if ($_GET['error'] == 1) {
                        echo "<div class='alert alert-danger' role='alert'>El nombre de usuario es obligatorio</div>";
                    }
                }
                ?>
                <!-- Campo Correo Electrónico -->
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="floatingInput" placeholder="ejemplo@correo.com"
                        name="email">
                    <label for="floatingInput">Ingrese un correo electrónico</label>
                </div>
                <?php
                if (isset($_GET['error'])) {
                    if ($_GET['error'] == 2) {
                        echo "<div class='alert alert-danger' role='alert'>El correo electrónico es obligatorio</div>";
                    } elseif ($_GET['error'] == 4) {
                        echo "<div class='alert alert-danger' role='alert'>El correo electrónico ya está registrado</div>";
                    }
                }

                ?>
                <!-- Campo Contraseña -->
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="floatingPassword" placeholder="••••••••"
                        name="password">
                    <label for="floatingPassword">Ingrese una contraseña</label>
                </div>
                <?php
                if (isset($_GET['error'])) {
                    if ($_GET['error'] == 3) {
                        echo "<div class='alert alert-danger' role='alert'>La contraseña es obligatoria</div>";
                    }
                }
                ?>
                <br>
                <!-- Botón de Enviar -->
                <button type="submit" class="btn btn-brown w-100 py-2">Crear cuenta</button>
            </form>
        </div>
    </div>
</body>

</html>