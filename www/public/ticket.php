<?php
require '../private/databaseController.php';
require '../vendor/setasign/fpdf/fpdf.php';
session_start();
$pdo = conexion();

if (!isset($_SESSION['id'])) {
    die("Error: Debes iniciar sesión.");
}

$id_usuario = $_SESSION['id'];

$stmt = $pdo->prepare("SELECT * FROM Vista_Pedidos_Detallada WHERE id_usuario = :id_usuario ORDER BY fecha_pedido DESC LIMIT 1");
$stmt->execute([':id_usuario' => $id_usuario]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    die("Error: No se encontró el pedido.");
}

$id_pedido = $pedido['id_pedido'];
$codigo_ticket = strtoupper(uniqid("TCK-")); 
$direccion_entrega = $pedido['direccion_sucursal']; 

$stmt = $pdo->prepare("SELECT p.nombre, dp.cantidad, dp.precio_unitario, (dp.cantidad * dp.precio_unitario) AS total 
                       FROM Detalles_pedido dp 
                       JOIN Productos p ON dp.id_producto = p.id 
                       WHERE dp.id_pedido = :id_pedido");
$stmt->execute([':id_pedido' => $id_pedido]);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(190, 10, 'Ticket de Compra', 0, 1, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(190, 10, "Codigo de Compra: $codigo_ticket", 0, 1);
$pdf->Cell(190, 10, "Fecha: " . $pedido['fecha_pedido'], 0, 1);
$pdf->Cell(190, 10, "Direccion de Entrega:", 0, 1);
$pdf->MultiCell(190, 10, $direccion_entrega, 0, 'L'); 
$pdf->Ln(5);
$pdf->Cell(60, 10, 'Producto', 1);
$pdf->Cell(30, 10, 'Cantidad', 1);
$pdf->Cell(40, 10, 'Precio Unitario', 1);
$pdf->Cell(40, 10, 'Total', 1);
$pdf->Ln();

foreach ($productos as $producto) { 
    $pdf->Cell(60, 10, mb_convert_encoding($producto['nombre'], 'ISO-8859-1', 'UTF-8'), 1);
    $pdf->Cell(30, 10, $producto['cantidad'], 1);
    $pdf->Cell(40, 10, '$' . number_format($producto['precio_unitario'], 2), 1);
    $pdf->Cell(40, 10, '$' . number_format($producto['total'], 2), 1);
    $pdf->Ln();
}

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(190, 10, "Total de la compra: $" . number_format($pedido['total'], 2), 0, 1, 'R');

// // Guardar PDF en el servidor
// $ruta_ticket = "../ticket_$id_pedido.pdf";
// $pdf->Output('F', $ruta_ticket);

$stmt = $pdo->prepare("INSERT INTO Tickets (id_pedido, id_sucursal, codigo_ticket, fecha_generacion) VALUES (:id_pedido, 1, :codigo, NOW())");
$stmt->execute([
    ':id_pedido' => $id_pedido, 
    ':codigo' => $codigo_ticket
]);

$pdf->Output('D', "ticket_$id_pedido.pdf");
?>