<?php
session_start();

// 1. CONTROL DE SEGURIDAD: Solo entran el Encargado de Almacén o el Gerente
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] !== 'almacen' && $_SESSION['usuario_rol'] !== 'gerente')) {
    header("Location: ../login.php");
    exit();
}

// 2. CONEXIÓN CON EL MODELO: Llamamos al modelo Producto para traer los datos
require_once __DIR__ . '/../../models/Producto.php';

$modeloProducto = new Producto();
$resultado = $modeloProducto->listarTodos(); // Ejecuta el SELECT * FROM productos
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Inventario de Almacén</title>
</head>
<body>

    <?php 
    require_once __DIR__ . '/../layout/header.php'; 
    require_once __DIR__ . '/../layout/nav.php'; 
    ?>

    <h2>Gestión de Inventario (CRUD Almacén)</h2>

    <p>
        <a href="nuevo_producto.php">Agregar nuevo producto</a>
    </p>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre del Insumo</th>
                <th>Categoría</th>
                <th>Precio Compra</th>
                <th>Precio Venta</th>
                <th>Stock Disponible</th>
                <th>Fecha Vencimiento</th>
                <th>Acción</th> </tr>
        </thead>
        <tbody>
            <?php 
            // Ciclo normal de PHP para recorrer cada fila de la base de datos
            while ($row = mysqli_fetch_array($resultado)) { 
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_producto'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_insumo'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['categoria'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['precio_compra'], ENT_QUOTES, 'UTF-8'); ?> Bs.</td>
                    <td><?php echo htmlspecialchars($row['precio_venta'], ENT_QUOTES, 'UTF-8'); ?> Bs.</td>
                    <td>
                        <?php 
                        // Alerta visual simple si el stock es menor o igual a 5 unidades
                        if ($row['stock_disponible'] <= 5) {
                            echo "<strong>" . htmlspecialchars($row['stock_disponible'], ENT_QUOTES, 'UTF-8') . " (STOCK CRÍTICO)</strong>";
                        } else {
                            echo htmlspecialchars($row['stock_disponible'], ENT_QUOTES, 'UTF-8');
                        }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['fecha_vencimiento'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <a href="editar_producto.php?id=<?php echo urlencode($row['id_producto']); ?>">Editar</a>
                    </td>
                </tr>
            <?php 
            } 
            ?>
        </tbody>
    </table>

    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>