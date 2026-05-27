<?php
session_start();

// 1. Control de seguridad básico
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] !== 'almacen' && $_SESSION['usuario_rol'] !== 'gerente')) {
    header("Location: ../login.php");
    exit();
}

// 2. Conexión directa a la Base de Datos
require_once __DIR__ . '/../../config/conexion.php';
$db = Conexion::conectar();

// 3. Capturar el ID de la URL (ej: editar_producto.php?id=1)
$id = intval($_GET['id'] ?? 0);

// 4. Consulta directa en bruto para traer los datos del producto viejo
$sql = "SELECT * FROM productos WHERE id_producto = $id LIMIT 1";
$resultado = mysqli_query($db, $sql);
$prod = mysqli_fetch_array($resultado);

// Si el ID no existe en la base de datos, frena el script
if (!$prod) {
    die("Producto no encontrado en el sistema.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Editar Producto</title>
</head>
<body>
    <a href="inventario.php">Volver al Inventario</a>
    <h2>Editar Insumo Existente</h2>

    <form action="../../controllers/InventarioController.php?action=editar" method="POST">
        
        <input type="hidden" name="id_producto" value="<?php echo $prod['id_producto']; ?>">

        <label>Nombre del Insumo:</label>
        <input type="text" name="nombre_insumo" value="<?php echo $prod['nombre_insumo']; ?>" required><br><br>

        <label>Categoría:</label>
        <select name="categoria" required>
            <option value="Fertilizantes" <?php if($prod['categoria'] == 'Fertilizantes') echo 'selected'; ?>>Fertilizantes</option>
            <option value="Pesticidas" <?php if($prod['categoria'] == 'Pesticidas') echo 'selected'; ?>>Pesticidas</option>
            <option value="Semillas" <?php if($prod['categoria'] == 'Semillas') echo 'selected'; ?>>Semillas</option>
            <option value="Riego" <?php if($prod['categoria'] == 'Riego') echo 'selected'; ?>>Riego</option>
        </select><br><br>

        <label>Precio de Compra (Bs):</label>
        <input type="number" step="0.01" name="precio_compra" value="<?php echo $prod['precio_compra']; ?>" required><br><br>

        <label>Precio de Venta (Bs):</label>
        <input type="number" step="0.01" name="precio_venta" value="<?php echo $prod['precio_venta']; ?>" required><br><br>

        <label>Stock Disponible:</label>
        <input type="number" name="stock_disponible" value="<?php echo $prod['stock_disponible']; ?>" required><br><br>

        <label>Fecha de Vencimiento:</label>
        <input type="text" name="fecha_vencimiento" value="<?php echo $prod['fecha_vencimiento']; ?>" required><br><br>

        <button type="submit">Actualizar Cambios</button>
    </form>
</body>
</html>