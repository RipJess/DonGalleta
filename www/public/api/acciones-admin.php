<?php
require_once '../../private/adminController.php';
header('Content-Type: application/json');
$admin = new adminController();
$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['action']) {
        case 'agregar':
            $response = $admin->agregaProducto(htmlspecialchars(trim($_POST['nombre'])), isset($_POST['sabores']) ? $_POST['sabores'] : [], htmlspecialchars(trim($_POST['descripcion'])), $_POST['precio'], $_POST['cantidad'], $_FILES['imagen']);
            header('Location: ../admin.php?success=1');
            break;
        case 'actualizar':
            $response = $admin->actualizaProducto($_POST['id_producto'], htmlspecialchars($_POST['nombre']), htmlspecialchars($_POST['descripcion']), $_POST['precio'], $_POST['stock'], $_POST['disponibilidad']);
            echo json_encode($response);
            break;
        case 'eliminar':
            $response = $admin->eliminaProducto($_POST['id_producto']);
            echo json_encode($response);
            break;
        case 'nuevo-sabor':
            $admin->agrega_sabor($_POST['nuevo_sabor']);
            header('Location: ../admin.php?success=2');
            break;
    }

    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    switch ($_GET['action']) {
        case 'obtenerProducto':
            $id_producto = $_GET['id'] ?? null;
        
            if (!$id_producto) {
                echo json_encode(['error' => 'No se ha encontrado el ID del producto']);
                exit();
            }
        
            $response = $admin->getProducto($id_producto);
            echo json_encode($response);
            break;
        
        case 'obtenerPedido':
            $id_pedido = $_GET['id_pedido'] ?? null;
            if (!$id_pedido) {
                echo json_encode(['error' => 'No se ha encontrado el ID del pedido']);
                exit();
            }
        
            $response = $admin->getPedido($id_pedido);
            echo json_encode($response);
            
            break;
    }


}
?>