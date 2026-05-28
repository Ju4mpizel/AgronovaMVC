<?php
// Activar reportes de error para depuración del docente
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/conexion.php';

class Ruta {
    private $db;


    public function __construct() {
        $this->db = Conexion::conectar();
    }

    // LEER: Obtener los pedidos que necesitan atención del chofer (Pendientes o En Ruta)
    public function listarPedidosParaEntrega() {
        $sql = "SELECT p.id_pedido, c.nombre_completo AS cliente, c.direccion, c.zona, c.telefono,
                       pr.nombre_insumo AS producto, p.cantidad, p.estado_entrega, p.fecha_registro
                FROM pedidos p
                INNER JOIN clientes c ON p.id_cliente = c.id_cliente
                INNER JOIN productos pr ON p.id_producto = pr.id_producto
                WHERE p.estado_entrega IN ('Pendiente', 'En Ruta')
                ORDER BY p.id_pedido ASC";
        
        return mysqli_query($this->db, $sql);
    }

    // ACTUALIZAR: Cambiar el estado del pedido ('En Ruta' o 'Entregado')
    public function actualizarEstado($id_pedido, $nuevo_estado) {
        $estadoEsc = mysqli_real_escape_string($this->db, $nuevo_estado);
        
        $sql = "UPDATE pedidos SET estado_entrega = '$estadoEsc' WHERE id_pedido = $id_pedido";
        return mysqli_query($this->db, $sql);
    }
}