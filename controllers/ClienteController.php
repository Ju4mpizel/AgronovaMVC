<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/Cliente.php';

class ClienteController {
    private $modelo;

    public function __construct() {
        $this->modelo = new Cliente();
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = array_map('htmlspecialchars', $_POST);
            $nombre = trim($_POST['nombre_completo'] ?? '');
            $ci_nit = trim($_POST['ci_nit'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');
            $zona = trim($_POST['zona'] ?? '');

            if (!empty($nombre) && !empty($ci_nit) && !empty($direccion) && !empty($zona)) {
                
                if ($this->modelo->existeNit($ci_nit)) {
                    die("Error: El CI o NIT proporcionado ya se encuentra registrado en el sistema.");
                }

                $exito = $this->modelo->crear($nombre, $ci_nit, $telefono, $direccion, $zona);
                
                if ($exito) {
                    header("Location: ../views/modules/clientes.php");
                    exit();
                } else {
                    echo "Error al registrar al cliente en la base de datos.";
                }
            } else {
                echo "Por favor, complete todos los campos obligatorios.";
            }
        }
    }

    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = array_map('htmlspecialchars', $_POST);
            $id = intval($_POST['id_cliente'] ?? 0);
            $nombre = trim($_POST['nombre_completo'] ?? '');
            $ci_nit = trim($_POST['ci_nit'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');
            $zona = trim($_POST['zona'] ?? '');

            if ($id > 0 && !empty($nombre) && !empty($ci_nit) && !empty($direccion) && !empty($zona)) {
                
                $exito = $this->modelo->actualizar($id, $nombre, $ci_nit, $telefono, $direccion, $zona);
                
                if ($exito) {
                    header("Location: ../views/modules/clientes.php");
                    exit();
                } else {
                    echo "Error al actualizar los datos del cliente.";
                }
            } else {
                echo "Datos no válidos para la actualización.";
            }
        }
    }

    // CORRECCIÓN HISTORIAL SEGURIDAD: Se eliminó por completo la función borrar() 
    // para evitar que se destruya la integridad referencial de los pedidos vinculados.
}

if (isset($_GET['action'])) {
    $controller = new ClienteController();
    
    if ($_GET['action'] === 'registrar') {
        $controller->registrar();
    }
    if ($_GET['action'] === 'editar') {
        $controller->editar();
    }
    // CORRECCIÓN: Se quitó el detonador de 'eliminar' para que no pueda ser forzado por URL.
}