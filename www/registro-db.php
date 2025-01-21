<?php
include('utilities.php');
$debug = false;
$pdo = conexion();
session_start();

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["nombre"])) {
        $nombre = trim($_POST["nombre"]);
        $email = trim($_POST["email"]);
        $contrasena = trim($_POST["password"]);
        
        if (empty($email) || empty($contrasena) || empty($nombre)) { // Para un mejor fronted separar por caso y dar mensaje para cada validacion
            header("Location: login.php?error=1");
            exit();
        }
        
        try {
            $sql = "INSERT INTO Usuarios (nombre, email, password, rol) VALUES (:nombre, :email, SHA2( :password, 256), 'cliente')";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $contrasena, PDO::PARAM_STR);
            $stmt->execute();
            
            if ($debug) {
                echo "Usuario registrado";
            }

            header("Location: login.php");
            
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                echo "El correo ya está registrado";
            } else {
                echo "Error: " . $e->getMessage();
            }
        }
    } else {
        header("Location: registro.php?error=1");
        exit();
    }
}
?>