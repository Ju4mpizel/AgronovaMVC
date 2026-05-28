<?php
session_start();

// CÓDIGO GUARDIÁN DE SEGURIDAD - INTACTO
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] !== 'ventas' && $_SESSION['usuario_rol'] !== 'gerente')) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/conexion.php';
$db = Conexion::conectar();

$id = intval($_GET['id'] ?? 0);
$sql = "SELECT * FROM clientes WHERE id_cliente = $id LIMIT 1";
$resultado = mysqli_query($db, $sql);
$cliente = mysqli_fetch_array($resultado);

if (!$cliente) {
    die("Error: Cliente no encontrado en el sistema.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Editar Cliente</title>
</head>
<body>

    <!-- INCLUSIÓN DE LA ESTRUCTURA PERSISTENTE -->
    <?php 
    require_once __DIR__ . '/../layout/header.php'; 
    require_once __DIR__ . '/../layout/nav.php'; 
    ?>

    <h2>Editar Información del Cliente</h2>
    <p><a href="clientes.php">◄ Cancelar y Volver</a></p>

    <form action="../../controllers/ClienteController.php?action=editar" method="POST">
        <input type="hidden" name="id_cliente" value="<?php echo htmlspecialchars($cliente['id_cliente'], ENT_QUOTES, 'UTF-8'); ?>">

        <label>Nombre Completo / Razón Social:</label><br>
        <input type="text" name="nombre_completo" value="<?php echo htmlspecialchars($cliente['nombre_completo'], ENT_QUOTES, 'UTF-8'); ?>" required><br><br>

        <label>Documento de Identidad / NIT:</label><br>
        <input type="text" name="ci_nit" value="<?php echo htmlspecialchars($cliente['ci_nit'], ENT_QUOTES, 'UTF-8'); ?>" required><br><br>

        <label>Teléfono / Celular:</label><br>
        <input type="text" name="telefono" value="<?php echo htmlspecialchars($cliente['telefono'], ENT_QUOTES, 'UTF-8'); ?>"><br><br>

        <label>Dirección:</label><br>
        <input type="text" name="direccion" value="<?php echo htmlspecialchars($cliente['direccion'], ENT_QUOTES, 'UTF-8'); ?>" required><br><br>

        <label>Zona / Ciudad:</label><br>
        <input type="text" name="zona" value="<?php echo htmlspecialchars($cliente['zona'], ENT_QUOTES, 'UTF-8'); ?>" required><br><br>

        <button type="submit">Actualizar Cambios del Cliente</button>
    </form>

    <!-- INCLUSIÓN DEL CIERRE -->
    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>