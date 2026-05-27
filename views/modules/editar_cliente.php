<?php
session_start();

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
    <a href="clientes.php">Volver a Clientes</a>
    <h2>Editar Información del Cliente</h2>

    <form action="../../controllers/ClienteController.php?action=editar" method="POST">
        
        <input type="hidden" name="id_cliente" value="<?php echo $cliente['id_cliente']; ?>">

        <label>Nombre Completo / Razón Social:</label><br>
        <input type="text" name="nombre_completo" value="<?php echo $cliente['nombre_completo']; ?>" required><br><br>

        <label>Documento de Identidad / NIT:</label><br>
        <input type="text" name="ci_nit" value="<?php echo $cliente['ci_nit']; ?>" required><br><br>

        <label>Teléfono / Celular:</label><br>
        <input type="text" name="telefono" value="<?php echo $cliente['telefono']; ?>"><br><br>

        <label>Dirección:</label><br>
        <input type="text" name="direccion" value="<?php echo $cliente['direccion']; ?>" required><br><br>

        <label>Zona / Ciudad:</label><br>
        <input type="text" name="zona" value="<?php echo $cliente['zona']; ?>" required><br><br>

        <button type="submit">Actualizar Cambios del Cliente</button>
    </form>
</body>
</html>