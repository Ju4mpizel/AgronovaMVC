<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/Pedido.php';

class PedidosController {
    private $modelo;

    public function __construct() {
        $this->modelo = new Pedido();
    }

    public function procesarVenta() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = array_map('htmlspecialchars', $_POST);
            $id_cliente = intval($_POST['id_cliente'] ?? 0);
            $id_producto = intval($_POST['id_producto'] ?? 0);
            $cantidad = intval($_POST['cantidad'] ?? 0);
            $fecha_registro = trim($_POST['fecha_registro'] ?? '');

            if ($id_cliente > 0 && $id_producto > 0 && $cantidad > 0) {
                
                $productoData = $this->modelo->obtenerDatosProducto($id_producto);
                
                if (!$productoData) {
                    die("Error: El producto seleccionado no existe en el catálogo.");
                }

                $stock_actual = intval($productoData['stock_disponible']);
                $precio_venta = floatval($productoData['precio_venta']);

                if ($cantidad > $stock_actual) {
                    die("Error: Stock insuficiente. El stock disponible actual es de " . $stock_actual . " unidades.");
                }
                $total_pagar = $precio_venta * $cantidad;

                $exito = $this->modelo->registrarVenta($id_cliente, $id_producto, $cantidad, $total_pagar, $fecha_registro);
                
                if ($exito) {
                    header("Location: ../views/modules/lista_pedidos.php");
                    exit();
                } else {
                    echo "Error al procesar la transacción de venta.";
                }
            } else {
                echo "Por favor, introduzca datos válidos en el formulario de ventas.";
            }
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'vender') {
    $controller = new PedidosController();
    $controller->procesarVenta();
}