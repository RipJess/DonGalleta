<?php
session_start();
include('utilities.php');
$pdo = conexion();

$id_usuario = $_SESSION['id'];
$id_producto = $_POST['id_producto'];

try {
    $sql = "DELETE FROM Carrito WHERE id_usuario = :id_usuario AND id_producto = :id_producto";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_usuario' => $id_usuario,
        ':id_producto' => $id_producto,
    ]);

    echo "Producto eliminado.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>