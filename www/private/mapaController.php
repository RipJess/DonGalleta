<?php
session_start();
include_once 'databaseController.php';

class MapaUbicaciones {
    private $pdo;

    public function __construct() {
        $this->pdo = conexion();
    }

    public function obtenerSucursales() {
        $sucursales = [];
        $stmt = $this->pdo->prepare("SELECT nombre, direccion, latitud, longitud FROM Sucursales");
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultados as $fila) {
            $sucursales[] = [
                'nombre' => $fila['nombre'],
                'direccion' => $fila['direccion'],
                'latitud' => $fila['latitud'],
                'longitud' => $fila['longitud']
            ];
        }

        return $sucursales;
    }
}