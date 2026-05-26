<?php
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {

    public function login() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (!empty($username) && !empty($password)) {
                $model = new Usuario();
                $usuario = $model->verificarCredenciales($username, $password);

                if ($usuario) {
                    $_SESSION['usuario_id']     = $usuario['id_usuario'];
                    $_SESSION['usuario_nombre'] = $usuario['nombre_completo'];
                    $_SESSION['usuario_rol']    = $usuario['rol'];

                    header('Location: dashboard.php');
                    exit();
                } else {
                    $error = "Usuario o contraseña incorrectos.";
                }
            } else {
                $error = "Por favor, complete todos los campos.";
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
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $auth = new AuthController();
    $auth->logout();
}