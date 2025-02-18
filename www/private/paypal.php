<?php
session_start();
require_once 'databaseController.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

class PayPal
{
    private $pdo;
    private $id_usuario;

    public function __construct()
    {
        $this->pdo = conexion();
        $this->id_usuario = $_SESSION['id'];
    }

    public function completarPago($orderID, $details, $paypalData, $sucursal)
    {
        try {
            $this->pdo->beginTransaction();

            $sql = "INSERT INTO Pedidos (id_usuario, id_sucursal, fecha_pedido, estado, total) 
                    VALUES (:id_usuario, :id_sucursal, NOW(), 'completado', :total)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id_usuario' => $this->id_usuario,
                ':id_sucursal' => $sucursal,
                ':total' => $paypalData['purchase_units'][0]['amount']['value']
            ]);
            $id_pedido = $this->pdo->lastInsertId();

            $sql = "SELECT id_producto, cantidad 
                    FROM Carrito 
                    WHERE id_usuario = :id_usuario";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_usuario' => $this->id_usuario]);
            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($productos as $producto) {
                $sql = "INSERT INTO Detalles_pedido (id_pedido, id_producto, cantidad)
                        VALUES (:id_pedido, :id_producto, :cantidad)";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':id_pedido' => $id_pedido,
                    ':id_producto' => $producto['id_producto'],
                    ':cantidad' => $producto['cantidad']
                ]);
            }

            $sql = "INSERT INTO Pagos (id_pedido, metodo_pago, referencia, monto, fecha_pago, id_sucursal)
                    VALUES (:id_pedido, 'paypal', :referencia, :monto, NOW(), :id_sucursal)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id_pedido' => $id_pedido,
                ':referencia' => $orderID,
                ':monto' => $paypalData['purchase_units'][0]['amount']['value'],
                ':id_sucursal' => $sucursal
            ]);

            $stmt = $this->pdo->prepare("DELETE FROM Carrito WHERE id_usuario = :id_usuario");
            $stmt->execute([':id_usuario' => $this->id_usuario]);

            $this->pdo->commit();

            return ['success' => true, 'message' => 'Pedido completado correctamente'];
        } catch (PDOException $th) {
            $this->pdo->rollBack();
            return ['success' => false, 'message' => $th->getMessage()];
        }
    }

    public function validarPaypal($orderID)
    {
        $paypalClientID = "AadDx0jK0TElff2zyNBXSpvKdl0dIKPPxz44C-F960lcA65rPUiqUBnnbc9CGs360o9Su3DEtWVZdSGH";
        $paypalSecret = "EE2gIFXfJXXFSeqkG9d071TWG3b1ddmcaWmwzee-Tse-ml3pcif9_UDe_Wx8GXMLODW_JPvcb-MY9hQ3";

        $ch = curl_init("https://api-m.sandbox.paypal.com/v2/checkout/orders/$orderID");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$paypalClientID:$paypalSecret");
        $response = curl_exec($ch);
        curl_close($ch);

        $paypalData = json_decode($response, true);

        return $paypalData;
    }
}

?>