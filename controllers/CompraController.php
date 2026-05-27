<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/Compra.php';

class CompraController {
    private $modelo;

    public function __construct() {
        $this->modelo = new Compra();
    }

    public function procesar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_producto = intval($_POST['id_producto'] ?? 0);
            $cantidad = intval($_POST['cantidad'] ?? 0);
            $fecha_compra = trim($_POST['fecha_compra'] ?? '');

            if ($id_producto > 0 && $cantidad > 0) {
                $exito = $this->modelo->registrarCompra($id_producto, $cantidad, $fecha_compra);
                
                if ($exito) {
                    // Si todo sale bien, lo mandamos a ver el inventario actualizado
                    header("Location: ../views/modules/inventario.php");
                    exit();
                } else {
                    echo "Error al procesar la transacción de compra.";
                }
            } else {
                echo "Por favor, introduzca datos válidos.";
            }
        }
    }
}

// Disparador directo del formulario
if (isset($_GET['action']) && $_GET['action'] === 'comprar') {
    $controller = new CompraController();
    $controller->procesar();
}