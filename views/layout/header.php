<?php
// views/layout/header.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova System</title>
</head>
<body>
    <header>
        <h1>AgroNova Distribuciones S.R.L.</h1>
        <p>Usuario: <strong><?php echo $_SESSION['usuario_nombre']; ?></strong> | Rol: <strong><?php echo ucfirst($_SESSION['usuario_rol']); ?></strong></p>
        <a href="/AgronovaMVC/controllers/AuthController.php?action=logout">Cerrar Sesión</a>
    </header>
    <hr>