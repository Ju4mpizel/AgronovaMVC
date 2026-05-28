<?php
require_once __DIR__ . '/../config/conexion.php';

class Compra {
    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    public function registrarCompra($id_producto, $cantidad, $fecha) {
        $sqlProd = "SELECT precio_compra FROM productos WHERE id_producto = $id_producto LIMIT 1";
        $resProd = mysqli_query($this->db, $sqlProd);
        $prod = mysqli_fetch_array($resProd);
        
        $total_costo = $prod['precio_compra'] * $cantidad;
        $fechaEsc = mysqli_real_escape_string($this->db, $fecha);

        $sqlCompra = "INSERT INTO compras (id_producto, cantidad, total_costo, fecha_compra) 
                      VALUES ($id_producto, $cantidad, $total_costo, '$fechaEsc')";
        $insertExito = mysqli_query($this->db, $sqlCompra);

        if ($insertExito) {
            $sqlStock = "UPDATE productos SET stock_disponible = stock_disponible + $cantidad WHERE id_producto = $id_producto";
            return mysqli_query($this->db, $sqlStock);
        }

        return false;
    }

    public function obtenerReporteCompras() {
        $sql = "SELECT 
                    co.id_compra, 
                    co.fecha_compra, 
                    co.cantidad, 
                    co.total_costo,
                    prod.nombre_insumo AS producto_nombre
                FROM compras co
                INNER JOIN productos prod ON co.id_producto = prod.id_producto
                ORDER BY co.id_compra DESC";
                
        $resultado = mysqli_query($this->db, $sql);
        
        $compras = [];
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($row = mysqli_fetch_assoc($resultado)) {
                $compras[] = $row;
            }
        }
        return $compras;
    }
}