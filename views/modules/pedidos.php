<?php
session_start();

// CÓDIGO GUARDIÁN DE SEGURIDAD - INTACTO
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] !== 'ventas' && $_SESSION['usuario_rol'] !== 'gerente')) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/conexion.php';
$db = Conexion::conectar();

$clientes = mysqli_query($db, "SELECT id_cliente, nombre_completo, ci_nit FROM clientes");
$productos = mysqli_query($db, "SELECT id_producto, nombre_insumo, stock_disponible FROM productos");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Registrar Pedido</title>
</head>
<body>

    <!-- INCLUSIÓN DE LA ESTRUCTURA PERSISTENTE -->
    <?php 
    require_once __DIR__ . '/../layout/header.php'; 
    require_once __DIR__ . '/../layout/nav.php'; 
    ?>

    <h2>Registrar Nuevo Pedido / Venta de Insumos</h2>

    <form action="../../controllers/PedidosController.php?action=vender" method="POST">
        <label>Seleccionar Cliente Destinatario:</label><br>
        <select name="id_cliente" required>
            <option value="">-- Seleccione un cliente registrado --</option>
            <?php while($c = mysqli_fetch_array($clientes)) { ?>
                <option value="<?php echo $c['id_cliente']; ?>">
                    <?php echo $c['nombre_completo'] . " (NIT/CI: " . $c['ci_nit'] . ")"; ?>
                </option>
            <?php } ?>
        </select>
        <br><br>

        <label>Seleccionar Insumo Agrícola:</label><br>
        <select name="id_producto" required>
            <option value="">-- Seleccione un producto del inventario --</option>
            <?php while($p = mysqli_fetch_array($productos)) { ?>
                <option value="<?php echo $p['id_producto']; ?>">
                    <?php echo $p['nombre_insumo'] . " (Stock actual: " . $p['stock_disponible'] . ")"; ?>
                </option>
            <?php } ?>
        </select>
        <br><br>

        <label>Cantidad a Vender (Unidades / Sacos):</label><br>
        <input type="number" name="cantidad" min="1" placeholder="Ej. 5" required>
        <br><br>

        <label>Fecha de Registro del Pedido:</label><br>
        <input type="text" name="fecha_registro" value="<?php echo date('Y-m-d H:i'); ?>" required>
        <br><br>

        <button type="submit">Procesar Venta y Descontar del Almacén</button>
    </form>

    <!-- INCLUSIÓN DEL CIERRE -->
    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>