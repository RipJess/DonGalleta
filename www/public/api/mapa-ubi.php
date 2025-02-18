<?php
require_once '../../private/mapaController.php';
header('Content-Type: application/json');

$mapa = new MapaUbicaciones();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['action']) {
        case 'obtener_sucursales':
            $sucursales = $mapa->obtenerSucursales();
            break;
        case 'buscar-punto-entrega':
            $sucursales = $mapa->buscarPuntoEntrega($_POST['busqueda']);
            break;
    }

    echo json_encode($sucursales);
    exit();
}

?>