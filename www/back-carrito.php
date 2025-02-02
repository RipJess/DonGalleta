<?php
session_start();
include('utilities.php');

class CarritoCompras {
    private $pdo;
    
    public function __construct() {
        $this->pdo = conexion();
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
    }

    public function obtenerProductosCarrito() {
        $productos = [];
        foreach ($_SESSION['carrito'] as $id_producto => $cantidad) {
            $stmt = $this->pdo->prepare("SELECT id_producto, nombre, precio, imagen FROM Productos WHERE id_producto = ?");
            $stmt->execute([$id_producto]);
            $producto = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($producto) {
                $producto['cantidad'] = $cantidad;
                $producto['subtotal'] = $cantidad * $producto['precio'];
                $productos[] = $producto;
            }
        }
        return $productos;
    }

    public function agregarProducto($id_producto) {
        $stmt = $this->pdo->prepare("SELECT disponibilidad FROM Productos WHERE id_producto = ?");
        $stmt->execute([$id_producto]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($producto && $producto['disponibilidad'] > 0) {
            if (isset($_SESSION['carrito'][$id_producto])) {
                $_SESSION['carrito'][$id_producto]++;
            } else {
                $_SESSION['carrito'][$id_producto] = 1;
            }
            return ['success' => true, 'message' => 'Producto agregado al carrito'];
        }
        return ['success' => false, 'message' => 'Producto no disponible'];
    }

    public function actualizarCantidad($id_producto, $cantidad) {
        if ($cantidad <= 0) {
            unset($_SESSION['carrito'][$id_producto]);
            return ['success' => true, 'message' => 'Producto eliminado del carrito'];
        }

        $stmt = $this->pdo->prepare("SELECT disponibilidad FROM Productos WHERE id_producto = ?");
        $stmt->execute([$id_producto]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($producto && $producto['disponibilidad'] >= $cantidad) {
            $_SESSION['carrito'][$id_producto] = $cantidad;
            return ['success' => true, 'message' => 'Cantidad actualizada'];
        }
        return ['success' => false, 'message' => 'Cantidad no disponible'];
    }

    public function obtenerTotal() {
        $total = 0;
        foreach ($this->obtenerProductosCarrito() as $producto) {
            $total += $producto['subtotal'];
        }
        return $total;
    }

    public function obtenerNumeroItems() {
        return array_sum($_SESSION['carrito']);
    }

    public function vaciarCarrito() {
        $_SESSION['carrito'] = [];
        return ['success' => true, 'message' => 'Carrito vaciado'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carrito = new CarritoCompras();
    $response = [];

    switch ($_POST['action']) {
        case 'agregar':
            $response = $carrito->agregarProducto($_POST['id_producto']);
            break;
        case 'actualizar':
            $response = $carrito->actualizarCantidad($_POST['id_producto'], $_POST['cantidad']);
            break;
        case 'obtener':
            $response = [
                'productos' => $carrito->obtenerProductosCarrito(),
                'total' => $carrito->obtenerTotal(),
                'num_items' => $carrito->obtenerNumeroItems()
            ];
            break;
        case 'vaciar':
            $response = $carrito->vaciarCarrito();
            break;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>