<?php
require_once '../../private/paypal.php';

header('Content-Type: application/json');


if (!isset($_SESSION['id'])) {
    echo json_encode(["error" => "Debes iniciar sesión."]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['orderID']) || !isset($data['details'])) {
    echo json_encode(["error" => "No se recibieron los datos del pedido."]);
    exit();
}

$orderID = $data['orderID'];
$details = $data['details'];
$sucursal = $data['sucursal'];
$response = [];

$paypal = new PayPal();
$paypalData = $paypal->validarPaypal($orderID);

if ($paypalData === null) {
    echo json_encode(["error" => "No se pudo conectar con PayPal."]);
    exit();
}

if (!isset($paypalData['status']) || $paypalData['status'] !== 'COMPLETED') {
    $response = ['error' => 'El pago no se completó correctamente.'];
} else {
    $response = $paypal->completarPago($orderID, $details, $paypalData, $sucursal);
}

echo json_encode($response);
?>