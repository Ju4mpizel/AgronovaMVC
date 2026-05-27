<?php
require_once __DIR__ . '/../controllers/AuthController.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit();
}

$mensajeError = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController = new AuthController();
    $mensajeError = $authController->login();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroNova - Iniciar Sesión</title>
</head>
<body>
    <div class="login-card">
        <h2>AgroNova System</h2>
        
        <?php if (!empty($mensajeError)): ?>
            <div class="alert-error">
            <?php echo htmlspecialchars($mensajeError); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Nombre de Usuario</label>
                <input type="text" name="username" id="username" placeholder="ejemplo.gerente" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-login">Iniciar sesión</button>
        </form>
    </div>

</body>
</html>