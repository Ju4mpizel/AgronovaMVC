<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/Producto.php';

class InventarioController {
    private $modelo;

    public function __construct() {
        $this->modelo = new Producto();
    }
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = array_map('htmlspecialchars', $_POST);
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
    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = array_map('htmlspecialchars', $_POST);
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
}

if (isset($_GET['action'])) {
    $controller = new InventarioController();
    
    if ($_GET['action'] === 'registrar') {
        $controller->registrar();
    }
    if ($_GET['action'] === 'editar') {
        $controller->editar();
    }
}