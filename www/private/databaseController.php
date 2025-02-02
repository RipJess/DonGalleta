<?php
function conexion()
{
    $dsn = "mysql:host=mysql;dbname=DonGalleta;charset=utf8mb4";
    $username = "root";
    $password = "DonGalleta123*_";

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $th) {
        die("No se puede conectar a la base de datos. Error: $th");
    }

    return $pdo;
}

function cerrarConexion(&$pdo)
{
    $pdo = null;
}

?>