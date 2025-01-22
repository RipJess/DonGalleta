<?php
include('utilities.php');
$debug = false;
$pdo = conexion();
session_start();

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    if (isset($_POST['nombre']) && isset($_POST['descripcion']) && isset($_POST['precio']) && isset($_POST['unidades'])) {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        // $sabor = $_POST['sabor'];
        $unidades = $_POST['unidades'];

        if (empty($nombre) || empty($descripcion) || empty($precio) || empty($unidades)) {
            header("Location: admin.php?error=1");
            exit();
        }
    }

    try {
        // $sql = "SELECT id_sabor FROM Sabores WHERE nombre = :sabor LIMIT 1";
        // $stmt = $pdo->prepare($sql);
        // $stmt->execute([':sabor' => $sabor]);
        // $sabor_id = $stmt->fetch(PDO::FETCH_ASSOC);

        // if (!$sabor_id) {
        //     header("Location: admin.php?error=2");
        //     exit();
        // }

        if ($unidades <= 0) {
            $disponibilidad = "0";
        }else {
            $disponibilidad = "1";
        }

        $sql = "INSERT INTO Productos (nombre, descripcion, precio, id_sabor, disponibilidad, unidades) 
                VALUES (:nombre, :descripcion, :precio, 1, :disponibilidad, :unidades)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':precio' => $precio,
            // ':sabor' => $sabor_id['id_sabor'],
            ':disponibilidad' => $disponibilidad,
            ':unidades' => $unidades
        ]);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// PARA VER EL DETALLE DEL PEDIDO, POSIBLEMENTE PASARLO A UN MODAL
$id_pedido = $_GET['id'];
echo "<h2>Detalle del Pedido $id_pedido</h2>";
try {
    $sql = "SELECT Productos.nombre, Detalles_pedido.cantidad, Detalles_pedido.precio_unitario 
            FROM Detalles_pedido 
            JOIN Productos ON Detalles_pedido.id_producto = Productos.id_producto 
            WHERE Detalles_pedido.id_pedido = :id_pedido";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_pedido' => $id_pedido]);

    echo "<table>";
    echo "<tr><th>Producto</th><th>Cantidad</th><th>Precio Unitario</th></tr>";
    while ($detalle = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td>{$detalle['nombre']}</td>
                <td>{$detalle['cantidad']}</td>
                <td>{$detalle['precio_unitario']}</td>
              </tr>";
    }
    echo "</table>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}



?>