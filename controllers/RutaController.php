<?php
// Activar reportes de error obligatorios
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/Ruta.php';

class RutaController {
    private $modelo;

    public function __construct() {
        $this->modelo = new Ruta();
    }

    public function cambiarEstado() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // REQUISITO DOCENTE: Sanitización estricta de todas las entradas POST
            $_POST = array_map('htmlspecialchars', $_POST);

            $id_pedido = intval($_POST['id_pedido'] ?? 0);
            $nuevo_estado = trim($_POST['estado_entrega'] ?? '');

            if ($id_pedido > 0 && (!empty($nuevo_estado))) {
                // Validar que el estado sea uno de los permitidos para evitar alteraciones
                if ($nuevo_estado === 'En Ruta' || $nuevo_estado === 'Entregado') {
                    
                    $exito = $this->modelo->actualizarEstado($id_pedido, $nuevo_estado);
                    
                    if ($exito) {
                        // Redirigir de vuelta a la hoja de rutas del chofer
                        header("Location: ../views/modules/rutas.php");
                        exit();
                    } else {
                        echo "Error al actualizar el estado de la entrega.";
                    }
                } else {
                    echo "Estado de entrega no válido.";
                }
            } else {
                echo "Datos insuficientes para procesar el cambio de ruta.";
            }
        }
    }
}

// DETONADOR DIRECTO DESDE EL FORMULARIO DE HOJA DE RUTA
if (isset($_GET['action']) && $_GET['action'] === 'actualizar_ruta') {
    $controller = new RutaController();
    $controller->cambiarEstado();
}