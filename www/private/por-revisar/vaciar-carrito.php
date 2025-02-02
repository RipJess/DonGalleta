<?php
session_start();
include('utilities.php');
$pdo = conexion();

if (!isset($_SESSION['id'])) {
    echo json_encode(["status" => "error", "message" => "Usuario no autenticado."]);
    exit();
}

$id_usuario = $_SESSION['id'];

try {
    // Eliminar todos los productos del carrito para este usuario
    $sql = "DELETE FROM Carrito WHERE id_usuario = :id_usuario";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_usuario' => $id_usuario]);

    echo json_encode(["status" => "success", "message" => "Carrito vaciado con éxito."]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
