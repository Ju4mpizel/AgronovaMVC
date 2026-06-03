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
                    header('Location: ../views/dashboard.php');
                    exit();
                } else {
                    header('Location: ../views/login.php?error=1');
                    exit();
                }
            } else {
                header('Location: ../views/login.php?error=1');
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
        header('Location: ../views/login.php');
        exit();
    }
}

if (isset($_GET['action'])) {
    $auth = new AuthController();
    
    if ($_GET['action'] === 'login') {
        $auth->login();
    }
    if ($_GET['action'] === 'logout') {
        $auth->logout();
    }
}