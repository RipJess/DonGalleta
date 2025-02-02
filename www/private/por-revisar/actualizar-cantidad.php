<?php
session_start();
include('utilities.php');
$pdo = conexion();

$id_usuario = $_SESSION['id'];
$id_producto = $_POST['id_producto'];
$cambio = $_POST['cambio']; // +1 o -1

try {
    $sql = "UPDATE Carrito 
            SET cantidad = GREATEST(cantidad + :cambio, 1)
            WHERE id_usuario = :id_usuario AND id_producto = :id_producto";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':cambio' => $cambio,
        ':id_usuario' => $id_usuario,
        ':id_producto' => $id_producto,
    ]);

    echo "Cantidad actualizada.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
