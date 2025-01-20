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

function imprimeUsuarios($pdo)
{
    $users = $pdo->query("SELECT * FROM Usuarios")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($users as $user) {
        echo $user["nombre"] . "<br>";
        echo $user["email"] . "<br>";
        echo $user["password"] . "<br><br>";
    }
}
?>