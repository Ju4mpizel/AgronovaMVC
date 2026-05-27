<?php
session_start();
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] !== 'ventas' && $_SESSION['usuario_rol'] !== 'gerente')) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Registrar Cliente</title>
</head>
<body>
    <a href="clientes.php">◄ Volver a Clientes</a>
    <h2>Registrar Nuevo Cliente Comercial</h2>

    <form action="../../controllers/ClienteController.php?action=registrar" method="POST">
        
        <label>Nombre Completo / Razón Social:</label><br>
        <input type="text" name="nombre_completo" placeholder="Ej. Asociación Agraria" required><br><br>

        <label>Documento de Identidad / NIT:</label><br>
        <input type="text" name="ci_nit" placeholder="Ej. 10203040" required><br><br>

        <label>Teléfono / Celular:</label><br>
        <input type="text" name="telefono" placeholder="Ej. 71234567"><br><br>

        <label>Dirección:</label><br>
        <input type="text" name="direccion" placeholder="Ej. Av. Blanco Galindo KM 5" required><br><br>

        <label>Zona / Ciudad:</label><br>
        <input type="text" name="zona" placeholder="Ej. Cochabamba" required><br><br>

        <button type="submit">Guardar Cliente</button>
    </form>
</body>
</html>