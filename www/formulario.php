<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Contacto</title>
    <link rel="stylesheet" href="formulario.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>

    <header>
        <div class="foto-logo">
            <img src="img/logo.png" />
        </div>
        <h1>Formulario de contacto</h1>
    </header>
    <!-- Sección de Contacto -->
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

        <!-- Mensaje de Confirmación -->
        <div id="confirmationMessage" class="confirmation-message" style="display: none;">
            <p>¡Gracias por tu mensaje! Nos pondremos en contacto contigo pronto.</p>
        </div>
    </div>

    <div class="homepage">
        <footer class="footer">
            <div class="frame">
                <div class="frame-2">
                    <div class="frame-3">
                        <img class="img" src="img/dongalletalogonofondonoletra.png" />
                        <div class="text-wrapper">Don Galleta</div>
                    </div>
                    <div class="frame-4">
                        <div class="text-wrapper-2">Acerca de nosotros</div>
                        <div class="text-wrapper-2">Entregas</div>
                        <div class="text-wrapper-2">Ayuda y Soporte</div>
                        <div class="text-wrapper-2">Blog</div>
                        <div class="text-wrapper-2">Colabora con nosotros</div>
                        <div class="text-wrapper-2">Contacto</div>
                    </div>
                </div>
                <div class="redes-sociales">
                    <div>
                        <img src="img/facebook-icon.svg" alt="">
                    </div>
                    <div>
                        <img src="img/twitter-icon.svg" alt="">
                    </div>
                    <div>
                        <img src="img/instagram-icon.svg" alt="">
                    </div>
                </div>
            </div>
        </footer>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <script src="formulario.js"></script>
</body>

</html>