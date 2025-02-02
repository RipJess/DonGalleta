<?php
session_start();
include('utilities.php');
$pdo = conexion();

if (!isset($_SESSION['id'])) {
    // Redirigir al login si no está autenticado
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id'];
$id_producto = $_POST['id_producto'];

try {
    // Verificar si el producto ya está en el carrito
    $sql = "SELECT * FROM Carrito WHERE id_usuario = :id_usuario AND id_producto = :id_producto";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_usuario' => $id_usuario,
        ':id_producto' => $id_producto,
    ]);

    if ($stmt->rowCount() > 0) {
        $sql = "UPDATE Carrito SET cantidad = cantidad + 1 WHERE id_usuario = :id_usuario AND id_producto = :id_producto";
    } else {
        $sql = "INSERT INTO Carrito (id_usuario, id_producto, cantidad) VALUES (:id_usuario, :id_producto, 1)";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_usuario' => $id_usuario,
        ':id_producto' => $id_producto,
    ]);

    header("Location: carrito.php");
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
