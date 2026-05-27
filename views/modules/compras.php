<?php
session_start();
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] !== 'almacen' && $_SESSION['usuario_rol'] !== 'gerente')) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/conexion.php';
$db = Conexion::conectar();

// Cargamos los productos existentes para el menú desplegable (select)
$productos = mysqli_query($db, "SELECT id_producto, nombre_insumo FROM productos");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Registrar Compra</title>
</head>
<body>
    <a href="../dashboard.php">Volver al Dashboard</a>
    <h2>Registrar Compra de Insumos (Abastecimiento de Almacén)</h2>

    <form action="../../controllers/CompraController.php?action=comprar" method="POST">
        
        <label>Seleccionar Insumo del Catálogo:</label>
        <select name="id_producto" required>
            <option value="">-- Seleccione un producto con stock 0 o bajo --</option>
            <?php while($row = mysqli_fetch_array($productos)) { ?>
                <option value="<?php echo $row['id_producto']; ?>"><?php echo $row['nombre_insumo']; ?></option>
            <?php } ?>
        </select>
        <br><br>

        <label>Cantidad Adquirida (Sacos/Unidades):</label>
        <input type="number" name="cantidad" min="1" required>
        <br><br>

        <label>Fecha de Compra:</label>
        <input type="text" name="fecha_compra" value="<?php echo date('Y-m-d'); ?>" required>
        <br><br>

        <button type="submit">Ingresar Compra e Incrementar Stock</button>
    </form>
</body>
</html>