<?php
require_once '../../private/mapaController.php';

$mapa = new MapaUbicaciones();
$sucursales = $mapa->obtenerSucursales();

header('Content-Type: application/json');
echo json_encode($sucursales);
?>