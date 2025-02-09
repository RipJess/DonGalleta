<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Compra</title>
    <link rel="stylesheet" href="formulario.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <header>
        <div class="foto-logo">
            <img src="img/logo.png" />
        </div>
        <h1>Confirmación de Compra</h1>
    </header>
    
    <div class="contact-form-section">
        <h2>Detalles de la Compra</h2>
        <form id="ticketForm" action="https://formsubmit.co/your@email.com" method="POST"> <!-- Aqui se reemplazaria por los correos de admin y del user-->
            <!-- Nombre -->
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" readonly>
            </div>

            <!-- Carrito -->
            <div class="mb-3">
                <label for="cart" class="form-label">Carrito</label>
                <textarea class="form-control" id="cart" name="cart" rows="4" readonly></textarea>
            </div>

            <!-- Número de referencia -->
            <div class="mb-3">
                <label for="reference" class="form-label">Número de Referencia</label>
                <input type="text" class="form-control" id="reference" name="reference" readonly>
            </div>

            <!-- Dirección de recogida -->
            <div class="mb-3">
                <label for="address" class="form-label">Dirección de Recogida</label>
                <input type="text" class="form-control" id="address" name="address" readonly>
            </div>

            <!-- Botón de Enviar -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-submit">Enviar Ticket</button>
            </div>
        </form>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Simulación de datos recuperados de la base de datos
            const ticketData = {
                name: "Juan Pérez",
                cart: "- Galletas de Chocolate x2\n- Pan dulce x1\n- Café en grano x1",
                reference: "1234567890",
                address: "Calle Falsa 123, Ciudad Ejemplo"
            };
            
            // Asignar valores a los campos
            document.getElementById("name").value = ticketData.name;
            document.getElementById("cart").value = ticketData.cart;
            document.getElementById("reference").value = ticketData.reference;
            document.getElementById("address").value = ticketData.address;
        });
    </script>
</body>

</html>
