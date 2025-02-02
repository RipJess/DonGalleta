<?php
require_once '../../private/carritoController.php';

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