<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/conexion.php';

class Pedido {
    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    public function obtenerDatosProducto($id_producto) {
        $sql = "SELECT stock_disponible, precio_venta FROM productos WHERE id_producto = $id_producto LIMIT 1";
        $resultado = mysqli_query($this->db, $sql);
        return mysqli_fetch_array($resultado);
    }

    public function registrarVenta($id_cliente, $id_producto, $cantidad, $total_pagar, $fecha_registro) {
        $fechaEsc = mysqli_real_escape_string($this->db, $fecha_registro);

        $sqlPedido = "INSERT INTO pedidos (id_cliente, id_producto, cantidad, total_pagar, estado_entrega, fecha_registro) 
                      VALUES ($id_cliente, $id_producto, $cantidad, $total_pagar, 'Pendiente', '$fechaEsc')";
        
        $insertExito = mysqli_query($this->db, $sqlPedido);
        if ($insertExito) {
            $sqlStock = "UPDATE productos SET stock_disponible = stock_disponible - $cantidad WHERE id_producto = $id_producto";
            return mysqli_query($this->db, $sqlStock);
        }

        return false;
    }

    public function obtenerReportePedidos() {
        $sql = "SELECT 
                    p.id_pedido, 
                    p.fecha_registro, 
                    p.cantidad,
                    p.total_pagar, 
                    p.estado_entrega,
                    c.nombre_completo AS cliente_nombre, 
                    prod.nombre_insumo AS producto_nombre
                FROM pedidos p
                INNER JOIN clientes c ON p.id_cliente = c.id_cliente
                INNER JOIN productos prod ON p.id_producto = prod.id_producto
                ORDER BY p.id_pedido DESC";
                
        $resultado = mysqli_query($this->db, $sql);
        
        $pedidos = [];
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($row = mysqli_fetch_assoc($resultado)) {
                $pedidos[] = $row;
            }
        }
        return $pedidos;
    }
}