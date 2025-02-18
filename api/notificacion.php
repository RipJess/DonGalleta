<?php
require '../private/config/database.php';
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function notificarAdministrador($id_pedido, $pdo) {
    $stmt = $pdo->prepare("SELECT u.email, u.nombre, p.total
                           FROM Pedidos p
                           JOIN Usuarios u ON p.id_usuario = u.id
                           WHERE p.id = ?");
    $stmt->execute([$id_pedido]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    $mail = new PHPMailer(true);
    try {
        $mail->setFrom('notificaciones@tuweb.com', 'Sistema de Pedidos');
        $mail->addAddress('admin@tuweb.com'); // Cambiar al correo del administrador
        $mail->Subject = "Nuevo Pedido #{$id_pedido}";
        $mail->Body = "Se ha recibido un nuevo pedido de {$pedido['nombre']} por un total de $" . number_format($pedido['total'], 2);
        $mail->send();
    } catch (Exception $e) {
        error_log("Error al enviar notificación: " . $mail->ErrorInfo);
    }
}
?>
