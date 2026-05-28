<?php
session_start();

// CÓDIGO GUARDIÁN DE SEGURIDAD - INTACTO
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] !== 'ventas' && $_SESSION['usuario_rol'] !== 'gerente')) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/conexion.php';
$db = Conexion::conectar();

$sql = "SELECT p.id_pedido, c.nombre_completo AS cliente, pr.nombre_insumo AS producto, 
               p.cantidad, p.total_pagar, p.estado_entrega, p.fecha_registro
        FROM pedidos p
        INNER JOIN clientes c ON p.id_cliente = c.id_cliente
        INNER JOIN productos pr ON p.id_producto = pr.id_producto
        ORDER BY p.id_pedido DESC";

$resultado = mysqli_query($db, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Historial de Pedidos</title>
</head>
<body>

    <!-- INCLUSIÓN DE LA ESTRUCTURA PERSISTENTE -->
    <?php 
    require_once __DIR__ . '/../layout/header.php'; 
    require_once __DIR__ . '/../layout/nav.php'; 
    ?>

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
                        <td><?php echo htmlspecialchars($row['id_pedido'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['cliente'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['producto'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['cantidad'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars(number_format($row['total_pagar'], 2), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><strong><?php echo htmlspecialchars($row['estado_entrega'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['fecha_registro'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                <?php 
                } 
            } else {
                echo "<tr><td colspan='7'>No se han registrado pedidos en el sistema.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- INCLUSIÓN DEL CIERRE -->
    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>