<?php
session_start();

// CÓDIGO GUARDIÁN DE SEGURIDAD - INTACTO
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] !== 'almacen' && $_SESSION['usuario_rol'] !== 'gerente')) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/conexion.php';
$db = Conexion::conectar();

$productos = mysqli_query($db, "SELECT id_producto, nombre_insumo, stock_disponible FROM productos");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Registrar Compra</title>
</head>
<body>

    <!-- INCLUSIÓN DE LA ESTRUCTURA PERSISTENTE -->
    <?php 
    require_once __DIR__ . '/../layout/header.php'; 
    require_once __DIR__ . '/../layout/nav.php'; 
    ?>

    <h2>Registrar Compra de Insumos (Abastecimiento de Almacén)</h2>

    <form action="../../controllers/CompraController.php?action=comprar" method="POST">
        <label>Seleccionar Producto / Insumo:</label><br>
        <select name="id_producto" required>
            <option value="">-- Seleccione un producto --</option>
            <?php while($p = mysqli_fetch_array($productos)) { ?>
                <option value="<?php echo $p['id_producto']; ?>">
                    <?php echo $p['nombre_insumo'] . " (Stock actual: " . $p['stock_disponible'] . ")"; ?>
                </option>
            <?php } ?>
        </select>
        <br><br>

        <label>Cantidad a Ingresar (Sacos / Unidades):</label><br>
        <input type="number" name="cantidad" min="1" placeholder="Ej. 50" required>
        <br><br>

        <label>Fecha de la Transacción:</label><br>
        <input type="text" name="fecha_compra" value="<?php echo date('Y-m-d H:i'); ?>" required>
        <br><br>

        <button type="submit">Registrar Compra e Incrementar Stock</button>
    </form>

    <!-- INCLUSIÓN DEL CIERRE -->
    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>