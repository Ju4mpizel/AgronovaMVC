<?php
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {

    public function login() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = array_map('htmlspecialchars', $_POST);
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (!empty($username) && !empty($password)) {
                $model = new Usuario();
                $usuario = $model->verificarCredenciales($username, $password);

                if ($usuario) {
                    $_SESSION['usuario_id']     = $usuario['id_usuario'];
                    $_SESSION['usuario_nombre'] = $usuario['nombre_completo'];
                    $_SESSION['usuario_rol']    = $usuario['rol'];

                    // CORRECCIÓN 1: Ruta absoluta para saltar correctamente de la carpeta controllers/ a views/
                    header('Location: /AgronovaMVC/views/dashboard.php');
                    exit();
                } else {
                    // CORRECCIÓN 2: Si falla, regresa enviando el error por la URL para el aviso dinámico
                    header('Location: /AgronovaMVC/views/login.php?error=1');
                    exit();
                }
            } else {
                header('Location: /AgronovaMVC/views/login.php?error=1');
                exit();
            }
        }
        return $error;
    }

    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = array();
        session_destroy();
        
        // CORRECCIÓN 3: Ruta absoluta limpia para el cierre de sesión seguro
        header('Location: /AgronovaMVC/views/login.php');
        exit();
    }
}

// CORRECCIÓN 4: DISPARADORES UNIFICADOS
// Ahora el archivo escucha de forma obligatoria tanto el inicio como el cierre de sesión
if (isset($_GET['action'])) {
    $auth = new AuthController();
    
    if ($_GET['action'] === 'login') {
        $auth->login();
    }
    if ($_GET['action'] === 'logout') {
        $auth->logout();
    }
}