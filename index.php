<?php
// 1. INICIAR EL SISTEMA DE SESIONES
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. LOGICA DE REDIRECCIÓN AUTOMÁTICA
if (isset($_SESSION['usuario_id'])) {
    // Si el trabajador ya tiene una sesión activa, lo mandamos directo al Dashboard
    header('Location: views/dashboard.php');
    exit();
} else {
    // Si no está logueado, lo redirigimos automáticamente a la pantalla de Login
    header('Location: views/login.php');
    exit();
}
?>