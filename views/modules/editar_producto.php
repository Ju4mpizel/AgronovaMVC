<?php
session_start();

// CÓDIGO GUARDIÁN DE SEGURIDAD - INTACTO
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] !== 'almacen' && $_SESSION['usuario_rol'] !== 'gerente')) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/conexion.php';
$db = Conexion::conectar();

$id = intval($_GET['id'] ?? 0);
$sql = "SELECT * FROM productos WHERE id_producto = $id LIMIT 1";
$resultado = mysqli_query($db, $sql);
$producto = mysqli_fetch_array($resultado);

if (!$producto) {
    die("Error: Producto no encontrado en el almacén.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Editar Producto</title>
</head>
<body>

    <!-- INCLUSIÓN DE LA ESTRUCTURA PERSISTENTE -->
    <?php 
    require_once __DIR__ . '/../layout/header.php'; 
    require_once __DIR__ . '/../layout/nav.php'; 
    ?>

    <h2>Editar Información del Insumo</h2>
    <p><a href="inventario.php">◄ Cancelar y Volver</a></p>

    <form action="../../controllers/InventarioController.php?action=editar" method="POST">
        <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($producto['id_producto'], ENT_QUOTES, 'UTF-8'); ?>">

        <label>Nombre del Insumo / Producto:</label><br>
        <input type="text" name="nombre_insumo" value="<?php echo htmlspecialchars($producto['nombre_insumo'], ENT_QUOTES, 'UTF-8'); ?>" required><br><br>

        <label>Categoría:</label><br>
        <input type="text" name="categoria" value="<?php echo htmlspecialchars($producto['categoria'], ENT_QUOTES, 'UTF-8'); ?>" required><br><br>

        <label>Precio de Compra (Bs.):</label><br>
        <input type="number" step="0.01" name="precio_compra" value="<?php echo htmlspecialchars($producto['precio_compra'], ENT_QUOTES, 'UTF-8'); ?>" required><br><br>

        <label>Precio de Venta (Bs.):</label><br>
        <input type="number" step="0.01" name="precio_venta" value="<?php echo htmlspecialchars($producto['precio_venta'], ENT_QUOTES, 'UTF-8'); ?>" required><br><br>

        <label>Stock Disponible:</label><br>
        <input type="number" name="stock_disponible" value="<?php echo htmlspecialchars($producto['stock_disponible'], ENT_QUOTES, 'UTF-8'); ?>" required><br><br>

        <label>Fecha de Vencimiento:</label><br>
        <input type="text" name="fecha_vencimiento" value="<?php echo htmlspecialchars($producto['fecha_vencimiento'], ENT_QUOTES, 'UTF-8'); ?>" required><br><br>

        <button type="submit">Actualizar Producto</button>
    </form>

    <!-- INCLUSIÓN DEL CIERRE -->
    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>