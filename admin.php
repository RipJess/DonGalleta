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
                        <?php
                        try {
                            $sql = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.imagen, p.stock, p.disponibilidad, GROUP_CONCAT(s.nombre ORDER BY s.nombre SEPARATOR ', ') AS sabores
                                    FROM Productos p
                                    LEFT JOIN Producto_sabor ps ON p.id = ps.id_producto
                                    LEFT JOIN Sabores s ON ps.id_sabor = s.id
                                    GROUP BY p.id";

                            $stmt = $pdo->query($sql);
                            $stmt->execute();
                            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($productos as $producto) {
                                echo "<tr>
                                <td style='text-align: left;'> $producto[nombre]</td>
                                <td style='text-align: center;'> $producto[sabores] </td>
                                <td> $producto[descripcion]</td>
                                <td style='text-align: center;'> $producto[precio]</td>
                                <td style='text-align: center;'> $producto[stock]</td>
                                <td style='text-align: center;'>" . ($producto['disponibilidad'] ? "si" : "no") . "</td>
                                <td>
                                    <div class='botones-accion'>
                                        <button type='button' class='btn btn-outline-danger btn-sm btn-accion' data-bs-toggle='modal' data-bs-target='#actualizarProducto' data-id='{$producto['id']}'>Actualizar</button>
                                        <button type='button' class='btn btn-outline-danger btn-sm btn-accion' data-bs-toggle='modal' data-bs-target='#eliminarProducto' data-id='{$producto['id']}'>Eliminar</button>
                                    </div>
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
            <button type='button' class="btn btn-outline-danger btn-sm btn-accion" data-bs-target="#agrega-sabor"
                data-bs-toggle="modal">¿Nuevo sabor?</button>

            <div class='alert alert-dismissible fade show d-none' id="mensaje" role='alert'>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>

            <?php
            if (isset($_GET['success'])) {
                switch ($_GET['success']) {
                    case '0':
                        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                No se ha podido agregar el sabor.
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>";
                        break;
                    case '1':
                        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>¡Éxito!</strong> Producto agregado correctamente.
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>";
                        break;
                    case '2':
                        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                    <strong>¡Éxito!</strong> Sabor agregado correctamente.
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>";
                        break;
                    case '3':
                        echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                                    Ya existe el sabor.
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>";
                        break;

                }
            }
            ?>
        </section>

        <!-- Actualizar un producto -->
        <div class="modal fade" id="actualizarProducto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Actualizar producto</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="actualizar_producto">
                            <input type="hidden" id="id_producto" name="id_producto">

                            <label for="nombre_prod_act">Nombre:</label>
                            <input type="text" id="nombre_prod_act" name="nombre" class="form-control" required>

                            <label for="descripcion_prod_act">Descripción:</label>
                            <textarea id="descripcion_prod_act" name="descripcion" class="form-control"></textarea>

                            <label for="precio_prod_act">Precio:</label>
                            <input type="number" id="precio_prod_act" name="precio" class="form-control" step="0.01"
                                required>

                            <label for="stock_prod_act">Stock:</label>
                            <input type="number" id="stock_prod_act" name="stock" class="form-control" required>

                            <label for="disponibilidad_prod_act">Disponibilidad:</label>
                            <select id="disponibilidad_prod_act" name="disponibilidad" class="form-control">
                                <option value="1">Sí</option>
                                <option value="0">No</option>
                            </select>
                        </form>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger btn-sm btn-accion"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-outline-danger btn-sm btn-accion" data-bs-dismiss="modal"
                            id="guardarCambios">Guardar cambios</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agregar un producto -->
        <div class="modal fade" id="agregarProducto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Agregar producto</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="agrega-producto" action="./api/acciones-admin.php" method="post">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="New York"
                                    required>
                                <label for="nombre">Nombre del producto</label>
                            </div>

                            <div class="dropdown">
                                <button class="btn btn-outline-danger btn-sm btn-accion" style="width:100%"
                                    type="button" id="multiSelectDropdown" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Seleccione el sabor
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="multiSelectDropdown">
                                    <?php
                                    try {
                                        $sql = "SELECT id, nombre FROM Sabores";
                                        $stmt = $pdo->query($sql);
                                        $sabores = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($sabores as $sabor) {
                                            echo "
                                            <li>
                                                <input type='checkbox' name='sabores[]' id='{$sabor['nombre']}' value='{$sabor['id']}'>
                                                <label for='{$sabor['nombre']}'>{$sabor['nombre']}</label>
                                            </li>";
                                        }
                                    } catch (PDOException $e) {
                                        echo "Error " . $e->getMessage();
                                    }
                                    ?>
                                </ul>
                            </div>
                            <div class="form-floating">
                                <textarea class="form-control" name="descripcion" id="descripcion"
                                    placeholder="New York"></textarea>
                                <label for="descripcion">Descripcion</label>
                            </div>
                            <div class="form-floating">
                                <input type="number" class="form-control" name="precio" id="precio" step="0.01"
                                    placeholder="10.00" required>
                                <label for="precio">Precio (MXN)</label>
                            </div>
                            <div class="form-floating">
                                <input type="number" class="form-control" name="cantidad" id="cantidad"
                                    placeholder="100" required>
                                <label for="cantidad">Cantidad en Inventario</label>
                            </div>
                            <div class="input-group mb-3">
                                <input type="file" name="imagen" accept="image/" class="form-control" id="inputGroupFile">
                                <label class="input-group-text" for="inputGroupFile">Subir</label>
                            </div>

                            <hr>
                            <?php
                            if (isset($_GET['error'])) {
                                if ($_GET['error'] == 1) {
                                    echo "<div class='alert alert-danger' role='alert'>Favor de llenar todos los campos</div>";
                                }
                            }
                            ?>
                            <input type='hidden' name='action' value='agregar'>
                            <button type="Submit" class="btn btn-outline-danger btn-sm btn-accion">Guardar</button>
                            <button type="button" class="btn btn-outline-danger btn-sm btn-accion"
                                data-bs-dismiss="modal">Cancelar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agregar un sabor -->
        <div class="modal fade" id="agrega-sabor" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
            tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Agregar Sabor</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="./api/acciones-admin.php" method="post">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="nuevo_sabor" id="nuevo_sabor"
                                    placeholder="Nuevo Sabor" required>
                                <label for="nuevo_sabor">Nuevo Sabor</label>
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-sm btn-accion"
                                data-bs-dismiss="modal">cancelar</button>
                            <input type='hidden' name='action' value='nuevo-sabor'>
                            <button type="submit" class="btn btn-outline-danger btn-sm btn-accion">Agregar
                                Sabor</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Eliminar un producto -->
        <div class="modal fade" id="eliminarProducto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
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
                        <button type="button" class="btn btn-outline-danger btn-sm btn-accion" data-bs-dismiss="modal"
                            id="confirmaEliminar">Eliminar</button>
                        <button type="button" class="btn btn-outline-danger btn-sm btn-accion"
                            data-bs-dismiss="modal">cancelar</button>
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
                            $sql = "SELECT * FROM Pedidos_Usuarios ORDER BY fecha_pedido DESC";
                            $stmt = $pdo->query($sql);

                            while ($pedido = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>
                                <td>{$pedido['id_pedido']}</td>
                                <td>{$pedido['cliente']}</td>
                                <td>{$pedido['fecha_pedido']}</td>
                                <td>{$pedido['estado']}</td>
                                <td>{$pedido['total']}</td>
                                <td>
                                     <button type='button' class='btn btn-outline-danger btn-sm btn-accion' data-bs-toggle='modal' data-bs-target='#detallePedido' data-id='{$pedido['id_pedido']}'>Ver detalles</button>
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
        <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Detalles del pedido</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping">id del pedido</span>
                        <input class="form-control" id="detalle_id" type="text" disabled readonly>
                    </div>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping">Cliente</span>
                        <input class="form-control" id="detalle_nombre" type="text" disabled readonly>
                    </div>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping">Fecha</span>
                        <input class="form-control" id="detalle_fecha" type="text" disabled readonly>
                    </div>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping">Estado</span>
                        <input class="form-control" id="detalle_estado" type="text" disabled readonly>
                    </div>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping">Total</span>
                        <input class="form-control" id="detalle_total" type="text" disabled readonly>
                    </div>
                    <hr>
                    <h4>Productos</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="detalle_productos"></tbody>
                    </table>

                </div>
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