<?php
session_start();
include_once 'databaseController.php';

class MapaUbicaciones
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = conexion();
    }

    public function obtenerSucursales(): array
    {
        $sucursales = [];
        $stmt = $this->pdo->prepare("SELECT id, nombre, direccion, latitud, longitud FROM Sucursales");
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultados as $fila) {
            $sucursales[] = [
                'id' => $fila['id'],
                'nombre' => $fila['nombre'],
                'direccion' => $fila['direccion'],
                'latitud' => $fila['latitud'],
                'longitud' => $fila['longitud']
            ];
        }
        return $sucursales;
    }

    public function buscarPuntoEntrega($busqueda)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT nombre, direccion, latitud, longitud 
                                                FROM Sucursales 
                                                WHERE nombre LIKE :busqueda OR direccion LIKE :busqueda");
            $stmt->execute([':busqueda' => '%' . $busqueda . '%']);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                $sucursal = [
                    'nombre' => $resultado['nombre'],
                    'direccion' => $resultado['direccion'],
                    'latitud' => $resultado['latitud'],
                    'longitud' => $resultado['longitud']
                ];
                return ['success' => true, 'sucursal' => $sucursal];
            }
            return ['success' => false, 'message' => 'No se ha encontrado un punto de entraga.'];

        } catch (PDOException $th) {
            return ['error' => $th->getMessage()];
        }
    }
}