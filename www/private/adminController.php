<?php
session_start();
require_once 'databaseController.php';

class adminController
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = conexion();
    }

    function agregaProducto($nombre, $sabores, $descripcion, $precio, $cantidad, $imagen)
    {
        if (isset($nombre) && isset($sabores) && isset($descripcion) && isset($precio) && isset($cantidad)) {
            if (empty($nombre) || empty($sabores) || empty($descripcion) || empty($precio) || empty($cantidad)) {
                header("Location: ../admin.php?error=1");
                exit();
            }

            if (!empty($imagen)) {
                if ($imagen['error'] === 0) {
                    $apiKey = "TU_API_KEY_IMGBB";
                    $imagenData = base64_encode(file_get_contents($imagen['tmp_name']));
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "https://api.imgbb.com/1/upload?key=$apiKey");
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, ['image' => $imagenData]);
                    $respuesta = json_decode(curl_exec($ch), true);
                    curl_close($ch);

                    if (isset($respuesta['data']['url'])) {
                        $imagenUrl = $respuesta['data']['url'];
                    } else {
                        header("Location: ../admin.php?error=2");
                    }
                } else {
                    header("Location: ../admin.php?error=2");
                }
            } else {
                $imagenUrl = null;
            }

            try {
                $this->pdo->beginTransaction();
                $disponibilidad = 1;

                if ($cantidad <= 0) {
                    $disponibilidad = 0;
                    $cantidad = 0;
                }

                $sql = "INSERT INTO Productos (nombre, descripcion, precio, imagen, disponibilidad, stock) 
                    VALUES (:nombre, :descripcion, :precio, :imagen, :disponibilidad, :stock)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':nombre' => $nombre,
                    ':descripcion' => $descripcion,
                    ':precio' => $precio,
                    ':imagen' => $imagenUrl,
                    ':disponibilidad' => $disponibilidad,
                    ':stock' => $cantidad
                ]);

                $id_producto = $this->pdo->lastInsertId();

                $sql_sabores = "INSERT INTO Producto_sabor (id_producto, id_sabor) VALUES (:id_producto, :id_sabor)";
                $stmt_sabores = $this->pdo->prepare($sql_sabores);

                foreach ($sabores as $id_sabor) {
                    $stmt_sabores->execute([
                        ':id_producto' => $id_producto,
                        ':id_sabor' => $id_sabor
                    ]);
                }

                $this->pdo->commit();
                return ['success' => true, 'message' => 'Producto agregado correctamente'];

            } catch (PDOException $e) {
                $this->pdo->rollBack();
                return ["error" => $e->getMessage()];

            }
        } else {
            return ['success' => false, 'message' => 'Favor de llenar todos los campos'];
        }
    }

    function actualizaProducto($id_producto, $nombre, $descripcion, $precio, $stock, $disponibilidad)
    {
        $nombre = trim($nombre);
        $descripcion = trim($descripcion);
        if (empty($id_producto) || empty($nombre) || empty($precio) || empty($stock)) {
            echo "Error: Todos los campos son obligatorios.";
            exit;
        }
        try {
            if ($stock <= 0) {
                $disponibilidad = 0;
                $stock = 0;
            }

            $sql = "UPDATE Productos SET nombre = :nombre, descripcion = :descripcion, precio = :precio, stock = :stock, disponibilidad = :disponibilidad WHERE id = :id_producto";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':precio' => $precio,
                ':stock' => $stock,
                ':disponibilidad' => $disponibilidad,
                ':id_producto' => $id_producto
            ]);
            return ['success' => true, 'message' => 'Producto actualizado correctamente!'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'No se ha podido actualizar el producto!'];
        }
    }

    function eliminaProducto($id_producto)
    {
        if (isset($id_producto)) {
            try {
                $stmt = $this->pdo->prepare("CALL EliminarProducto(:id_producto)");
                $stmt->execute([':id_producto' => $id_producto]);
                return ['success' => true, 'message' => 'El producto ha sido eliminado'];
            } catch (PDOException $e) {
                return ['success' => false, 'message' => 'No se ha podido eliminar el producto!'];
            }
        } else {
            return ['success' => false, 'message' => 'No es posible eliminar el producto'];
        }

    }

    function agrega_sabor($nuevo_sabor)
    {
        if (isset($nuevo_sabor)) {
            $nuevo_sabor = trim($nuevo_sabor);
            if (empty($nuevo_sabor)) {
                header("Location: ../admin.php?success=0");
                exit();
            }

            try {
                $nuevo_sabor = strtolower($nuevo_sabor);

                $sql = "SELECT nombre FROM Sabores WHERE nombre = :nuevo_sabor LIMIT 1";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([':nuevo_sabor' => $nuevo_sabor]);
                $sabor = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($sabor) {
                    header("Location: ../admin.php?success=3");
                    exit();
                }

                $sql = "INSERT INTO Sabores (nombre) VALUES (:nuevo_sabor)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([':nuevo_sabor' => $nuevo_sabor]);

            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            header("Location: ../admin.php?success=0");
            exit();
        }
    }
    function getProducto($id_producto)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM Productos WHERE id = :id");
            $stmt->execute([':id' => $id_producto]);
            $producto = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($producto) {
                return $producto;
            } else {
                return ['error' => 'No se ha encontrado el producto'];
            }

        } catch (PDOException $e) {
            return ['Error' => $e->getMessage()];
        }
    }

    function getPedido($id_pedido)
    {
        try {
            $sql = "SELECT * FROM Pedidos_Usuarios WHERE id_pedido = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id_pedido]);
            $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$pedido) {
                return ['error' => 'No se ha encontrado el pedido'];
            }

            $sql = "SELECT p.nombre, dp.cantidad, dp.precio_unitario, dp.subtotal
                    FROM Detalles_pedido dp
                    JOIN Productos p ON dp.id_producto = p.id
                    WHERE dp.id_pedido = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id_pedido]);
            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $pedido['productos'] = $productos;
            return $pedido;

        } catch (PDOException $e) {
            return ['Error' => $e->getMessage()];
        }
    }
}
?>