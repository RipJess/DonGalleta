<?php
require_once '../../private/carritoController.php';
if (!isset($_SESSION['id'])) {
    header('Location: ../login.php');
    exit();
}
;
$carrito = new CarritoCompras();
$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['action']) {
        case 'agregar':
            if (!isset($_POST['id_producto'])) {
                $response = ['sucess' => false, 'message' => 'No se ha encontrado el ID del producto'];
                break;
            }
            $response = $carrito->agregarProducto($_POST['id_producto']);
            break;
        case 'cargar':
            $response = $carrito->obtenerProductosCarrito();
            break;
        case 'actualizar':
            if (!isset($_POST['id_producto']) || !isset($_POST['cantidad'])) {
                $response = ['sucess' => false, 'message' => 'No se ha encontrado el ID del producto o la cantidad'];
                break;
            }
            $response = $carrito->actualizarCantidad($_POST['id_producto'], $_POST['cantidad']);
            break;
        case 'vaciar':
            $response = $carrito->vaciarCarrito();
            break;
        case 'precioTotal':
            $response = $carrito->precioTotal();
            break;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>