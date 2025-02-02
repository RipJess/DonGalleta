

function imprimeCarrito($pdo)
{
    $id_usuario = $_SESSION['id'];

    try {
        $sql = "SELECT c.id_producto, p.nombre, p.precio, c.cantidad, (p.precio * c.cantidad) AS subtotal
            FROM Carrito c
            JOIN Productos p ON c.id_producto = p.id_producto
            WHERE c.id_usuario = :id_usuario";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id_usuario' => $id_usuario]);
        $carrito = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $carrito;

    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

?>