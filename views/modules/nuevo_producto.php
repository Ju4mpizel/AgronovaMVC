<?php
session_start();

// CÓDIGO GUARDIÁN DE SEGURIDAD - INTACTO
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] !== 'almacen' && $_SESSION['usuario_rol'] !== 'gerente')) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Nuevo Producto</title>
</head>
<body>

    <!-- INCLUSIÓN DE LA ESTRUCTURA PERSISTENTE -->
    <?php 
    require_once __DIR__ . '/../layout/header.php'; 
    require_once __DIR__ . '/../layout/nav.php'; 
    ?>

    <h2>Registrar Nuevo Insumo Agrícola</h2>
    <p><a href="inventario.php">◄ Volver al Inventario</a></p>

    <form action="../../controllers/InventarioController.php?action=registrar" method="POST">
        <label>Nombre del Insumo / Producto:</label><br>
        <input type="text" name="nombre_insumo" placeholder="Ej. Urea Fertilizante" required><br><br>

        <label>Categoría:</label><br>
        <input type="text" name="categoria" placeholder="Ej. Fertilizantes" required><br><br>

        <label>Precio de Compra (Bs.):</label><br>
        <input type="number" step="0.01" name="precio_compra" placeholder="Ej. 120.50" required><br><br>

        <label>Precio de Venta (Bs.):</label><br>
        <input type="number" step="0.01" name="precio_venta" placeholder="Ej. 150.00" required><br><br>

        <label>Stock Inicial Disponible:</label><br>
        <input type="number" name="stock_disponible" placeholder="Ej. 10" required><br><br>

        <label>Fecha de Vencimiento:</label><br>
        <input type="text" name="fecha_vencimiento" placeholder="AAAA-MM-DD" value="2027-12-31" required><br><br>

        <button type="submit">Guardar Producto en Catálogo</button>
    </form>

    <!-- INCLUSIÓN DEL CIERRE -->
    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>