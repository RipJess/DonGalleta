<?php
session_start();
require 'databaseController.php';

class Productos
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = conexion();
    }
    public function mostrarProductos($busqueda, $orden)
    {
        $ordenes_permitidos = [
            'precio_asc' => 'precio ASC',
            'precio_desc' => 'precio DESC',
            'nombre_asc' => 'nombre_producto ASC',
            'nombre_desc' => 'nombre_producto DESC',
            'sabor_asc' => 'sabores ASC',
            'sabor_desc' => 'sabores DESC'
        ];
        
        $orden = $ordenes_permitidos[$orden] ?? 'precio DESC';

        if ($busqueda === 'all') {
            try {
                $sql = "SELECT p.id_producto, p.nombre_producto, p.precio, p.imagen, p.sabores
                        FROM Vista_Productos_Catalogo p
                        WHERE p.disponibilidad = '1'
                        ORDER BY $orden";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return $productos;
            } catch (PDOException $th) {
                return ['success' => false, 'message' => $th->getMessage()];
            }
        } else {
            try {
                $sql = "SELECT p.id_producto, p.nombre_producto, p.precio, p.imagen, p.sabores
                        FROM Vista_Productos_Catalogo p
                        WHERE (p.nombre_producto LIKE :busqueda OR p.sabores LIKE :busqueda)
                        AND p.disponibilidad = '1'
                        ORDER BY $orden";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([':busqueda' => '%' . $busqueda . '%',]);
                $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return $productos;
            } catch (PDOException $th) {
                return ['success' => false, 'error' => $th->getMessage()];
            }
        }
    }
}
?>