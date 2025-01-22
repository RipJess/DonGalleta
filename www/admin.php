<?php
session_start();
include('utilities.php');
$pdo = conexion();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es-MX">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./admin.css">
    <link rel="stylesheet" href="./globals.css">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Berkshire+Swash&family=Inter:opsz,wght@14..32,100..900&family=Poppins:ital,wght@0,400;0,500;1,500&display=swap');
    </style>
    <title>Don Galleta | Admin</title>
</head>

<body>

    <header>
        <h1>Gestión de Inventario de Galletas</h1>
    </header>

    <div class="container">
        <!-- Sección para ver productos -->
        <section class="productos">
            <h2>Productos Disponibles</h2>
            <table id='tabla-productos'>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Sabor</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Unidades</th>
                        <th>Disponibilidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        $sql = "SELECT * FROM Productos";
                        $stmt = $pdo->query($sql);

                        while ($producto = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $sql = "SELECT nombre FROM Sabores WHERE id_sabor = :id_sabor";
                            $stmt2 = $pdo->prepare($sql);
                            $stmt2->execute([':id_sabor' => $producto['id_sabor']]);
                            $sabor = $stmt2->fetch(PDO::FETCH_ASSOC);
                            echo "<tr>
                            <td> $producto[nombre]</td>
                            <td> $sabor[nombre] </td>
                            <td> $producto[descripcion]</td>
                            <td> $producto[precio]</td>
                            <td> $producto[unidades]</td>
                            <td>" . ($producto['disponibilidad'] ? "si" : "no") . "</td>
                            <td>
                                <button class='btn-actualizar' data-id='$producto[id_producto]'>Actualizar</button>
                                <button class='btn-eliminar' data-id='$producto[id_producto]'>Eliminar</button>
                            </td>
                        </tr>";
                        }
                    } catch (PDOException $th) {
                        echo "Error: " . $th->getMessage();
                    }
                    ?>
                </tbody>
            </table>
            <button id="btn-agregar-producto">Agregar Producto</button>
        </section>

        <!-- Modal para agregar/actualizar productos -->
        <div id="modal-producto" class="modal">
            <div class="modal-contenido">
                <h3>Agregar/Actualizar Producto</h3>
                <form method="post" action="admin-db.php">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>

                    <!-- <label for="cantidad">Sabor</label>
                    <input type="number" id="can    tidad" name="sabor" required> -->

                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" required></textarea>

                    <label for="precio">Precio (MXN):</label>
                    <input type="number" id="precio" name="precio" step="0.01" required>

                    <label for="cantidad">Cantidad en Inventario:</label>
                    <input type="number" id="cantidad" name="unidades" required>

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
                        <th>id del pedido</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        $sql = "SELECT Pedidos.id_pedido, Usuarios.nombre AS cliente, Pedidos.fecha_pedido, Pedidos.estado, Pedidos.total 
                            FROM Pedidos 
                            JOIN Usuarios ON Pedidos.id_usuario = Usuarios.id_usuario";
                        $stmt = $pdo->query($sql);

                        while ($pedido = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>
                                <td>{$pedido['id_pedido']}</td>
                                <td>{$pedido['cliente']}</td>
                                <td>{$pedido['fecha_pedido']}</td>
                                <td>{$pedido['estado']}</td>
                                <td>{$pedido['total']}</td>
                                <td>
                                    <a href='admin-db.php?id={$pedido['id_pedido']}'>Ver Detalle</a>
                                </td>
                              </tr>";
                        }
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                    ?>
                </tbody>
            </table>
        </section>

    </div>

    <script src="admin.js"></script>
</body>

</html>