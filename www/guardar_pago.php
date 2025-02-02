<?php
header("Content-Type: application/json");
include 'config.php'; // Conexión a la BD

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id_pago'], $data['fecha'], $data['total'], $data['id_pedido'], $data['referencia'])) {
    $id_pago = $data['id_pago'];
    $id_pedido = $data['id_pedido']; // Debes obtener este ID del pedido correspondiente
    $referencia = $data['referencia']; // Puede ser el ID de PayPal o similar
    $monto = $data['total'];
    $fecha_pago = $data['fecha'];

    $stmt = $conn->prepare("INSERT INTO Pagos (id_pedido, referencia, monto, fecha_pago) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issd", $id_pedido, $referencia, $monto, $fecha_pago);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Datos incompletos"]);
}
?>
