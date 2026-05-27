<?php
session_start();
// Control de seguridad básico para el almacenero o gerente
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] !== 'almacen' && $_SESSION['usuario_rol'] !== 'gerente')) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Catalogar Producto</title>
</head>
<body>
    <a href="inventario.php">◄ Volver al Inventario</a>
    <h2>Registrar Nuevo Insumo en el Catálogo</h2>
    <p><em>Nota: El producto se registrará inicialmente con Stock: 0. Para cargarle inventario, deberá usar el módulo de Compras.</em></p>

    <form action="../../controllers/InventarioController.php?action=registrar" method="POST">
        
        <label>Nombre del Insumo Agrícola:</label><br>
        <input type="text" name="nombre_insumo" placeholder="Ej. Urea en Polvo YPFB" required><br><br>

        <label>Categoría:</label><br>
        <select name="categoria" required>
            <option value="Fertilizantes">Fertilizantes</option>
            <option value="Pesticidas">Pesticidas</option>
            <option value="Semillas">Semillas</option>
            <option value="Riego">Riego</option>
        </select><br><br>

        <label>Precio de Compra Base (Bs):</label><br>
        <input type="number" step="0.01" name="precio_compra" placeholder="0.00" required><br><br>

        <label>Precio de Venta al Público (Bs):</label><br>
        <input type="number" step="0.01" name="precio_venta" placeholder="0.00" required><br><br>

        <label>Fecha de Vencimiento:</label><br>
        <input type="text" name="fecha_vencimiento" placeholder="AAAA-MM-DD" required><br><br>

        <button type="submit">Guardar en Catálogo (Crear con Stock 0)</button>
    </form>
</body>
</html>