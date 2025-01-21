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
    <title>Don Galleta | Registro</title>
</head>

<body>
    <div class="container">
        <div class="login-card mx-auto">
            <div class="logo-container">
                <img src="img/logo.png" alt="Don Galleta Logo" class="img-fluid">
            </div>

            <!-- Formulario con método POST -->
            <form method="POST" action="registro-db.php">

                <!-- Campo Usuario -->
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingInput" placeholder="ejemplo@correo.com"
                        name="nombre">
                    <label for="floatingInput">Ingrese un nombre de usuario</label>
                </div>

                <!-- Campo Correo Electrónico -->
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="floatingInput" placeholder="ejemplo@correo.com"
                        name="email">
                    <label for="floatingInput">Ingrese un correo electrónico</label>
                </div>

                <!-- Campo Contraseña -->
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="floatingPassword" placeholder="••••••••"
                        name="password">
                    <label for="floatingPassword">Ingrese una contraseña</label>
                </div>
                <br>
                <!-- Botón de Enviar -->
                <button type="submit" class="btn btn-brown w-100 py-2">Crear cuenta</button>
            </form>
        </div>
    </div>
</body>

</html>