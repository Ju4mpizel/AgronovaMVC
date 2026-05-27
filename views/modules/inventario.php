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

    <p>
        <a href="../dashboard.php">◄ Volver al Dashboard</a> | 
        <a href="nuevo_producto.php">Agregar nuevo producto</a>
    </p>

    <h2>Gestión de Inventario (CRUD Almacén)</h2>

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
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Ciclo normal de PHP para recorrer cada fila de la base de datos
            while ($row = mysqli_fetch_array($resultado)) { 
            ?>
                <tr>
                    <td><?php echo $row['id_producto']; ?></td>
                    <td><?php echo $row['nombre_insumo']; ?></td>
                    <td><?php echo $row['categoria']; ?></td>
                    <td><?php echo $row['precio_compra']; ?> Bs.</td>
                    <td><?php echo $row['precio_venta']; ?> Bs.</td>
                    <td>
                        <?php 
                        // Alerta visual simple si el stock es menor o igual a 5 unidades
                        if ($row['stock_disponible'] <= 5) {
                            echo "<strong>" . $row['stock_disponible'] . " (STOCK CRÍTICO)</strong>";
                        } else {
                            echo $row['stock_disponible'];
                        }
                        ?>
                    </td>
                    <td><?php echo $row['fecha_vencimiento']; ?></td>
                    <td>
                        <a href="editar_producto.php?id=<?php echo $row['id_producto']; ?>">Editar</a> | 
    
                        <a href="../../controllers/InventarioController.php?action=eliminar&id=<?php echo $row['id_producto']; ?>" 
                            onclick="return confirm('¿Seguro que quieres eliminar este insumo?');">
                            Eliminar
                        </a>
                    </td>
                </tr>
            <?php 
            } 
            ?>
        </tbody>
    </table>

</body>
</html>