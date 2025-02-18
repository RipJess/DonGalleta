<?php
session_start();
require_once 'databaseController.php';

class CarritoCompras
{
    private $pdo;
    private $id_usuario;

    public function __construct()
    {
        $this->pdo = conexion();
        $this->id_usuario = $_SESSION['id'];
    }

    public function obtenerProductosCarrito()
    {
        try {
            $sql = "SELECT *
                    FROM Vista_Carrito_Usuario
                    WHERE id_usuario = :id_usuario";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_usuario' => $this->id_usuario]);
            $carrito = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $carrito;
        } catch (PDOException $th) {
            return ['success' => false, 'message' => $th->getMessage()];
        }
    }

    public function agregarProducto($id_producto)
    {
        try {
            $sql = "SELECT stock, disponibilidad FROM Productos WHERE id = :idProducto";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':idProducto' => $id_producto]);
            $producto = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$producto) {
                return ['success' => false, 'message' => 'El producto no existe'];
            } elseif ($producto['disponibilidad'] <= 0) {
                return ['success' => false, 'message' => 'El producto no esta disponible'];
            }

            $sql = "SELECT cantidad FROM Carrito WHERE id_usuario = :idUsuario AND id_producto = :idProducto";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':idUsuario' => $this->id_usuario,
                ':idProducto' => $id_producto
            ]);
            $existeProducto = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existeProducto) {
                $nueva_cantidad = $existeProducto['cantidad'] + 1;
                if ($nueva_cantidad > $producto['stock']) {
                    return ['success' => false, 'message' => 'No hay suficiente stock.'];
                }

                $sql = "UPDATE Carrito SET cantidad = cantidad + 1 WHERE id_usuario = :id_usuario AND id_producto = :id_producto";
            } else {
                if ($producto['stock'] <= 0) {
                    return ['success' => false, 'message' => 'No hay suficiente stock.'];
                }
                $sql = "INSERT INTO Carrito (id_usuario, id_producto, cantidad) VALUES (:id_usuario, :id_producto, 1)";
            }

            $this->pdo->prepare($sql)->execute(["id_usuario" => $this->id_usuario, "id_producto" => $id_producto]);
            return ['success' => true, 'message' => 'Producto agregado al carrito'];

        } catch (PDOException $th) {
            return ['success' => false, 'message' => $th->getMessage()];
        }
    }
    public function actualizarCantidad($id_producto, $cantidad)
    {
        try {
            $sql = "SELECT stock, disponibilidad FROM Productos WHERE id = :idProducto";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':idProducto' => $id_producto]);
            $producto = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$producto) {
                return ['success' => false, 'message' => 'El producto no existe'];
            } elseif ($producto['disponibilidad'] <= 0) {
                return ['success' => false, 'message' => 'El producto ya no esta disponible'];
            }

            if ($cantidad > $producto['stock']) {
                return ['success' => false, 'message' => 'No puedes agregar más unidades.'];
            }

            if ($cantidad > 0) {
                $sql = "UPDATE Carrito SET cantidad = :cantidad WHERE id_usuario = :id_usuario AND id_producto = :id_producto";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':cantidad' => $cantidad,
                    ':id_usuario' => $this->id_usuario,
                    ':id_producto' => $id_producto
                ]);
                return ['success' => true, 'message' => 'Cantidad actualizada'];
            } else {
                $sql = "DELETE FROM Carrito WHERE id_usuario = :id_usuario AND id_producto = :id_producto";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':id_usuario' => $this->id_usuario,
                    ':id_producto' => $id_producto
                ]);
                return ['success' => true, 'message' => 'Producto eliminado'];
            }
        } catch (PDOException $th) {
            return ['success' => false, 'message' => $th->getMessage()];
        }
    }

    public function vaciarCarrito()
    {
        try {
            $sql = "DELETE FROM Carrito WHERE id_usuario = :id_usuario";
            $this->pdo->prepare($sql)->execute([':id_usuario' => $this->id_usuario]);
            return ['success' => true, 'message' => 'Carrito vaciado con exito.'];
        } catch (PDOException $th) {
            return ['success' => false, 'message' => $th->getMessage()];
        }
    }

    public function precioTotal()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT total FROM Vista_Total_Carrito WHERE id_usuario = :id_usuario");
            $stmt->execute([':id_usuario' => $this->id_usuario]);
            $totalCarrito = $stmt->fetchColumn();
    
            return ['success' => true, "total" => $totalCarrito ?: 0];
        } catch (PDOException $th) {
            return ['success' => false, 'message' => $th->getMessage()];
        }
    }
}
?>