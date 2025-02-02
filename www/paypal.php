<!DOCTYPE html>
<html lang="es-MX">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago con PayPal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./paypal.css">
    <link rel="stylesheet" href="./globals.css">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
</head>

<body>
    <div class="frame-18">
        <div class="frame-21">
            <img class="img-fluid" src="img/logo.png" />
        </div>
        <div class="frame-19">
            <form class="recuadro_busqueda" method="get">
                <input type="text" id="buscar" placeholder="Buscar..." />
            </form>
            <div class="sign-in">
                <div class="btn-iniciar-sesion">
                    <a class="boton" href="login.php"> Iniciar sesión </a>
                </div>
            </div>
        </div>
    </div>

    <div class="payment-container">
        <h2 class="mb-3">Escoge tu método de pago</h2>
        <div id="paypal-button-container"></div>
    </div>

    <script src="https://www.paypal.com/sdk/js?client-id=AadDx0jK0TElff2zyNBXSpvKdl0dIKPPxz44C-F960lcA65rPUiqUBnnbc9CGs360o9Su3DEtWVZdSGH&currency=MXN" data-sdk-integration-source="developer-studio"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let total = sessionStorage.getItem('totalPago') || '0.00';
            let idPedido = sessionStorage.getItem('idPedido') || 0; // Asegúrate de obtenerlo correctamente

            paypal.Buttons({
                style: {
                    color: 'blue',
                    shape: 'pill',
                    label: 'pay'
                },
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: total
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(detalles) {
                        console.log(detalles);

                        // Enviar datos a PHP para guardarlos en la base de datos
                        fetch('guardar_pago.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    id_pedido: idPedido,
                                    referencia: detalles.id,
                                    total: total,
                                    fecha: detalles.create_time
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    window.location.href = "completado.php";
                                } else {
                                    alert("Error al guardar el pago: " + data.error);
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    });
                }
            }).render('#paypal-button-container');
        });
    </script>


</body>

</html>