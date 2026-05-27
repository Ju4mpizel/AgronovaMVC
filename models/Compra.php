<?php
require_once __DIR__ . '/../config/conexion.php';

class Compra {
    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    // Registra la compra y actualiza el stock del insumo
    public function registrarCompra($id_producto, $cantidad, $fecha) {
        // 1. Averiguar el precio de compra del producto para calcular el total matemático
        $sqlProd = "SELECT precio_compra FROM productos WHERE id_producto = $id_producto LIMIT 1";
        $resProd = mysqli_query($this->db, $sqlProd);
        $prod = mysqli_fetch_array($resProd);
        
        $total_costo = $prod['precio_compra'] * $cantidad;
        $fechaEsc = mysqli_real_escape_string($this->db, $fecha);

        // 2. Insertar el histórico en la tabla de compras
        $sqlCompra = "INSERT INTO compras (id_producto, cantidad, total_costo, fecha_compra) 
                      VALUES ($id_producto, $cantidad, $total_costo, '$fechaEsc')";
        $insertExito = mysqli_query($this->db, $sqlCompra);

        // 3. PASO CLAVE: Sumar la cantidad al stock_disponible actual del producto
        if ($insertExito) {
            $sqlStock = "UPDATE productos SET stock_disponible = stock_disponible + $cantidad WHERE id_producto = $id_producto";
            return mysqli_query($this->db, $sqlStock);
        }

        return false;
    }
}