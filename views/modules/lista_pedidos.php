<?php
session_start();

if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] !== 'ventas' && $_SESSION['usuario_rol'] !== 'gerente')) {
    header("Location: ../login.php");
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../config/conexion.php';
$db = Conexion::conectar();

// Consulta SQL en bruto con JOINs para armar el reporte en tiempo real
$sql = "SELECT p.id_pedido, c.nombre_completo AS cliente, pr.nombre_insumo AS producto, 
               p.cantidad, p.total_pagar, p.estado_entrega, p.fecha_registro
        FROM pedidos p
        INNER JOIN clientes c ON p.id_cliente = c.id_cliente
        INNER JOIN productos pr ON p.id_producto = pr.id_producto
        ORDER BY p.id_pedido DESC"; // Ordenados del más reciente al más antiguo

$resultado = mysqli_query($db, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Historial de Pedidos</title>
</head>
<body>

    <p>
        <a href="../dashboard.php">◄ Volver al Dashboard</a> | 
        <a href="pedidos.php">+ Registrar Nueva Venta (Pedido)</a>
    </p>

    <h2>Historial General de Pedidos y Ventas</h2>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Cliente</th>
                <th>Insumo Agrícola</th>
                <th>Cantidad</th>
                <th>Total a Pagar (Bs.)</th>
                <th>Estado Entrega</th>
                <th>Fecha Registro</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (mysqli_num_rows($resultado) > 0) {
                while ($row = mysqli_fetch_array($resultado)) { 
                ?>
                    <tr>
                        <td><?php echo $row['id_pedido']; ?></td>
                        <td><?php echo $row['cliente']; ?></td>
                        <td><?php echo $row['producto']; ?></td>
                        <td><?php echo $row['cantidad']; ?></td>
                        <td><?php echo number_format($row['total_pagar'], 2); ?></td>
                        <td>
                            <strong><?php echo $row['estado_entrega']; ?></strong>
                        </td>
                        <td><?php echo $row['fecha_registro']; ?></td>
                    </tr>
                <?php 
                } 
            } else {
                echo "<tr><td colspan='7'>No se han registrado pedidos en el sistema.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>