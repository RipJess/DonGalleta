<?php
$host = "localhost"; // Cambia si tu BD está en otro servidor
$dbname = "DonGalleta";
$username = "admin";
$password = "DonGalleta123*_";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
