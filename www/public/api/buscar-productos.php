<?php
require_once '../../private/productosController.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_GET['action'] === 'mostrar') {
        $orden = $_GET['orden'];
        $busqueda = $_GET['busqueda'];
    
        $productos = new Productos();
        $response = $productos->mostrarProductos($busqueda, $orden);
    
        echo json_encode($response);
        exit();
    }
}

?>