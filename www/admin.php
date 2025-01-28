<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario de Galletas</title>
    <link rel="stylesheet" href="admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <header>
        <div class="foto-logo">
            <img src="img/logo.png" />
        </div>
        <h1>Gestión de Inventario de Galletas</h1>
    </header>

    <div class="container">
        <!-- Sección para ver productos -->
        <section class="productos">
            <h2>Productos Disponibles</h2>
            <table id="tabla-productos">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Sabor</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí se llenarán los productos dinámicamente -->
                </tbody>
            </table>
            <button id="btn-agregar-producto">Agregar Producto</button>
        </section>

        <!-- Modal para agregar/actualizar productos -->
        <div id="modal-producto" class="modal">
            <div class="modal-contenido">
                <h3>Agregar/Actualizar Producto</h3>
                <form id="form-producto">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" required>
                    <label for="sabor">Sabor:</label>
                    <input type="text" id="sabor" required>

                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" required></textarea>

                    <label for="precio">Precio (MXN):</label>
                    <input type="number" id="precio" step="0.01" required>

                    <label for="cantidad">Cantidad en Inventario:</label>
                    <input type="number" id="cantidad" required>

                    <button type="submit">Guardar</button>
                    <button type="button" id="cerrar-modal">Cancelar</button>
                </form>
            </div>
        </div>

        <!-- Sección de Pedidos -->
        <section class="pedidos">
            <h2>Pedidos Recientes</h2>
            <table id="tabla-pedidos">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Productos</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí se llenarán los pedidos dinámicamente -->
                </tbody>
            </table>
        </section>
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

    <script src="admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
