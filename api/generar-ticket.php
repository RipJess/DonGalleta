<?php
require '../private/databaseController.php';
require '../vendor/autoload.php'; // Cargar FPDF

session_start();

if (!isset($_SESSION['id'])) {
    echo "Error: Debes iniciar sesión.";
    exit();
}

$id_pedido = $_GET['id_pedido'] ?? 0;

$stmt = $pdo->prepare("SELECT p.id, p.fecha_pedido, p.total, s.nombre AS sucursal
                       FROM Pedidos p
                       JOIN Sucursales s ON p.id_sucursal = s.id
                       WHERE p.id = ?");
$stmt->execute([$id_pedido]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    echo "Error: Pedido no encontrado.";
    exit();
}

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(190, 10, 'Ticket de Compra', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(190, 10, "Pedido No: " . $pedido['id'], 0, 1);
$pdf->Cell(190, 10, "Fecha: " . $pedido['fecha_pedido'], 0, 1);
$pdf->Cell(190, 10, "Sucursal: " . $pedido['sucursal'], 0, 1);
$pdf->Cell(190, 10, "Total: $" . number_format($pedido['total'], 2), 0, 1);
$pdf->Output("D", "ticket_pedido_{$pedido['id']}.pdf");
?>
