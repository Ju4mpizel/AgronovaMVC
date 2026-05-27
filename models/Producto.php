<?php
require_once __DIR__ . '/../config/conexion.php';

class Producto {
    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    // LEER: Obtener todos los productos para la tabla
    public function listarTodos() {
        $sql = "SELECT * FROM productos";
        return mysqli_query($this->db, $sql);
    }

    // CREAR: Insertar un nuevo insumo agrícola
    public function crear($nombre, $categoria, $p_compra, $p_venta, $stock, $vencimiento) {
        $nombreEsc = mysqli_real_escape_string($this->db, $nombre);
        $catEsc = mysqli_real_escape_string($this->db, $categoria);
        $vencEsc = mysqli_real_escape_string($this->db, $vencimiento);

        $sql = "INSERT INTO productos (nombre_insumo, categoria, precio_compra, precio_venta, stock_disponible, fecha_vencimiento) 
                VALUES ('$nombreEsc', '$catEsc', $p_compra, $p_venta, $stock, '$vencEsc')";
        
        return mysqli_query($this->db, $sql);
    }

    // LEER UNO: Obtener los datos de un solo producto para cargar el formulario de edición
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM productos WHERE id_producto = $id LIMIT 1";
        $resultado = mysqli_query($this->db, $sql);
        return mysqli_fetch_array($resultado);
    }

    // ACTUALIZAR: Guardar los cambios editados
    public function actualizar($id, $nombre, $categoria, $p_compra, $p_venta, $stock, $vencimiento) {
        $nombreEsc = mysqli_real_escape_string($this->db, $nombre);
        $catEsc = mysqli_real_escape_string($this->db, $categoria);
        $vencEsc = mysqli_real_escape_string($this->db, $vencimiento);

        $sql = "UPDATE productos SET 
                nombre_insumo = '$nombreEsc', 
                categoria = '$catEsc', 
                precio_compra = $p_compra, 
                precio_venta = $p_venta, 
                stock_disponible = $stock, 
                fecha_vencimiento = '$vencEsc' 
                WHERE id_producto = $id";
        
        return mysqli_query($this->db, $sql);
    }
}