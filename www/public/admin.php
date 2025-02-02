<?php
session_start();
require_once "../private/databaseController.php";
$pdo = conexion();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/globals.css">
    <link rel="stylesheet" href="./css/admin.css">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
    <title>Don Galleta | Admin</title>
</head>

<body>
    <header>
        <img class="img-fluid" src="img/logo.png" alt="logo" width="400">
        <h1>Gestión de Inventario de Galletas</h1>
    </header>

    <div class="container">
        <!-- Sección para ver productos -->
        <section class="productos">
            <h2>Productos Disponibles</h2>
            <div class="table-responsive">
                <table class="table table-hover" style="--bs-table-bg: transparent;">
                    <thead class="table-light" style="--bs-table-bg: transparent;">
                        <tr>
                            <th>Nombre</th>
                            <th style='text-align: center;'>Sabor</th>
                            <th>Descripción</th>
                            <th style='text-align: center;'>Precio</th>
                            <th style='text-align: center;'>Cantidad</th>
                            <th style='text-align: center;'>Disponibilidad</th>
                            <th style='text-align: center;'>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <!-- Aquí se llenarán los productos dinámicamente -->
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
                                <td style='text-align: left;'> $producto[nombre]</td>
                                <td style='text-align: center;'> $sabor[nombre] </td>
                                <td> $producto[descripcion]</td>
                                <td style='text-align: center;'> $producto[precio]</td>
                                <td style='text-align: center;'> $producto[unidades]</td>
                                <td style='text-align: center;'>" . ($producto['disponibilidad'] ? "si" : "no") . "</td>
                                <td class='botones-accion'>
                                    <button type='button' class='btn btn-outline-danger btn-sm btn-accion' data-bs-toggle='modal' data-bs-target='#actualizarProducto'>Actualizar</button>
                                    <button type='button' class='btn btn-outline-danger btn-sm btn-accion'  data-bs-toggle='modal' data-bs-target='#eliminarrProducto'>Eliminar</button>
                                </td>
                            </tr>";
                            }
                        } catch (PDOException $th) {
                            echo "Error: " . $th->getMessage();
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <button type='button' class='btn btn-outline-danger btn-sm btn-accion' data-bs-toggle='modal'
                data-bs-target='#agregarProducto'>Agregar producto</button>
        </section>
        <!-- Actualizar un producto -->
        <div class="modal fade" id="actualizarProducto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Actualizar producto</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Actualizar un producto dinamicamente -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger btn-sm btn-accion"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-outline-danger btn-sm btn-accion">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Agregar un producto -->
        <div class="modal fade" id="agregarProducto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Agregar producto</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="agrega-producto">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="nombre" placeholder="New York" required>
                                <label for="nombre">Nombre del producto</label>
                            </div>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="sabor" placeholder="Chocolate" required>
                                <label for="sabor">Sabor</label>
                            </div>
                            <div class="form-floating">
                                <textarea class="form-control" id="descripcion" placeholder="New York"></textarea>
                                <label for="descripcion">Descripcion</label>
                            </div>
                            <div class="form-floating">
                                <input type="number" class="form-control" id="precio" step="0.01" placeholder="10.00"
                                    required>
                                <label for="precio">Precio (MXN)</label>
                            </div>
                            <div class="form-floating">
                                <input type="number" class="form-control" id="cantidad" placeholder="100" required>
                                <label for="cantidad">Cantidad en Inventario</label>
                            </div>
                            <hr>
                            <button type="button" class="btn btn-outline-danger btn-sm btn-accion"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="Submit" class="btn btn-outline-danger btn-sm btn-accion">Guardar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Eliminar un producto -->
        <div class="modal fade" id="eliminarrProducto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Eliminar producto</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas eliminar este producto?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-danger btn-sm btn-accion" data-bs-target="#eliminaAcepta"
                            data-bs-toggle="modal">Aceptar</button>
                        <button class="btn btn-outline-danger btn-sm btn-accion"
                            data-bs-dismiss="modal">cancelar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Confirmación de eliminación -->
        <div class="modal fade" id="eliminaAcepta" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Eliminar producto</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>El producto ha sido eliminado</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger btn-sm btn-accion"
                            data-bs-dismiss="modal">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Sección de Pedidos -->
        <section class="pedidos">
            <h2>Pedidos Recientes</h2>
            <div class="table-responsive">
                <table class="table table-hover" style="--bs-table-bg: transparent;">
                    <thead class="table-light" style="--bs-table-bg: transparent;">
                        <tr>
                            <th>id del pedido</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <!-- Aquí se llenarán los productos dinámicamente -->
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
                                     <button type='button' class='btn btn-outline-danger btn-sm btn-accion' data-bs-toggle='modal' data-bs-target='#detallePedido'>Ver detalles</button>
                                </td>
                              </tr>";
                            }
                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <!-- ver detalle de producto -->
    <div class="modal fade" id="detallePedido" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Detalles del pedido</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- mostrar dinamicamente los detalles -->
                    </div>
                </div>
            </div>
        </div>

    <footer class="py-3 my-4">
        <ul class="nav justify-content-center border-bottom pb-3 mb-3">
            <li class="nav-item"><a href="index.php" class="nav-link px-2 text-body-secondary">Inicio</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">texto</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">texto</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">texto</a></li>
            <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">texto</a></li>
        </ul>
        <p class="text-center text-body-secondary">© 2025 Don Galleta</p>
    </footer>



    <script src="./js/admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>