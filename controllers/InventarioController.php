<?php
// 1. ACTIVAR REPORTES DE ERROR PARA EVITAR PANTALLAS EN BLANCO
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. IMPORTACIONES REQUERIDAS (Se agregó la conexión aquí para que el borrar funcione)
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/Producto.php';

class InventarioController {
    private $modelo;

    public function __construct() {
        $this->modelo = new Producto();
    }

    // Controla la acción de registrar uno nuevo
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre_insumo'] ?? '');
            $categoria = trim($_POST['categoria'] ?? '');
            $p_compra = floatval($_POST['precio_compra'] ?? 0);
            $p_venta = floatval($_POST['precio_venta'] ?? 0);
            $stock = intval($_POST['stock_disponible'] ?? 0);
            $vencimiento = trim($_POST['fecha_vencimiento'] ?? '');

            $exito = $this->modelo->crear($nombre, $categoria, $p_compra, $p_venta, $stock, $vencimiento);
            
            if ($exito) {
                header("Location: ../views/modules/inventario.php");
                exit();
            } else {
                echo "Error al registrar el producto.";
            }
        }
    }

    // Controla la acción de editar uno existente
    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id_producto'] ?? 0);
            $nombre = trim($_POST['nombre_insumo'] ?? '');
            $categoria = trim($_POST['categoria'] ?? '');
            $p_compra = floatval($_POST['precio_compra'] ?? 0);
            $p_venta = floatval($_POST['precio_venta'] ?? 0);
            $stock = intval($_POST['stock_disponible'] ?? 0);
            $vencimiento = trim($_POST['fecha_vencimiento'] ?? '');

            $exito = $this->modelo->actualizar($id, $nombre, $categoria, $p_compra, $p_venta, $stock, $vencimiento);
            
            if ($exito) {
                header("Location: ../views/modules/inventario.php");
                exit();
            } else {
                echo "Error al actualizar el producto.";
            }
        }
    }

    // Controla la acción de eliminar en bruto de la base de datos
    public function borrar() {
        // Capturamos el ID directo de la URL
        $id = intval($_GET['id'] ?? 0);

        if ($id > 0) {
            // Conexión directa
            $db = Conexion::conectar();
        
            // Consulta SQL directa para borrar el registro
            $sql = "DELETE FROM productos WHERE id_producto = $id";
            $exito = mysqli_query($db, $sql);
        
            if ($exito) {
                // Si lo borra con éxito, nos regresa de inmediato a la tabla de inventario
                header("Location: ../views/modules/inventario.php");
                exit();
            } else {
                echo "Error: No se puede eliminar este producto porque está asociado a un pedido o compra.";
            }
        }
    }
}

// DETONADORES DE ACCIÓN DIRECTA DESDE LOS FORMULARIOS Y ENLACES
if (isset($_GET['action'])) {
    $controller = new InventarioController();
    
    if ($_GET['action'] === 'registrar') {
        $controller->registrar();
    }
    if ($_GET['action'] === 'editar') {
        $controller->editar();
    }
    // CORRECCIÓN: Se añadió el detonador que faltaba para procesar la eliminación
    if ($_GET['action'] === 'eliminar') {
        $controller->borrar();
    }
}