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

    public function mostrarReporte() {
        return $this->modelo->obtenerReportePedidos();
    }

    public function processarVenta() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = array_map('htmlspecialchars', $_POST);
            $id_cliente = intval($_POST['id_cliente'] ?? 0);
            $id_producto = intval($_POST['id_producto'] ?? 0);
            $cantidad = intval($_POST['cantidad'] ?? 0);
            $fecha_registro = trim($_POST['fecha_registro'] ?? '');
            
            $accion_factura = isset($_POST['imprimir_factura']) ? true : false;

            if ($id_cliente > 0 && $id_producto > 0 && $cantidad > 0) {
                
                $productoData = $this->modelo->obtenerDatosProducto($id_producto);
                
                if (!$productoData) {
                    header("Location: ../views/modules/pedidos.php?error=no_existe");
                    exit();
                }

                $stock_actual = intval($productoData['stock_disponible']);
                $precio_venta = floatval($productoData['precio_venta']);

                if ($cantidad > $stock_actual) {
                    header("Location: ../views/modules/pedidos.php?error=stock_insuficiente&disponible=" . $stock_actual);
                    exit();
                }
                $total_pagar = $precio_venta * $cantidad;

                // CORRECCIÓN CLAVE: $resultado_pedido ahora contendrá el ID numérico real devuelto por el modelo
                $resultado_pedido = $this->modelo->registrarVenta($id_cliente, $id_producto, $cantidad, $total_pagar, $fecha_registro);
                
                if ($resultado_pedido !== false) {
                    if ($accion_factura) {
                        // Enviamos el ID real de forma directa y limpia
                        header("Location: ../views/modules/pedidos.php?imprimir_id=" . $resultado_pedido);
                    } else {
                        header("Location: ../views/modules/lista_pedidos.php");
                    }
                    exit();
                } else {
                    header("Location: ../views/modules/pedidos.php?error=db_error");
                    exit();
                }
            } else {
                header("Location: ../views/modules/pedidos.php?error=datos_invalidos");
                exit();
            }
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'vender') {
    $controller = new PedidosController();
    $controller->processarVenta();
}