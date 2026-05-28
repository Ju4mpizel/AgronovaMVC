<?php
session_start();

// CÓDIGO GUARDIÁN DE SEGURIDAD - INTACTO
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] !== 'chofer' && $_SESSION['usuario_rol'] !== 'gerente')) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/conexion.php';
$db = Conexion::conectar();

$sql = "SELECT p.id_pedido, c.nombre_completo AS cliente, c.zona, c.direccion,
               pr.nombre_insumo AS producto, p.cantidad, p.total_pagar, p.fecha_registro
        FROM pedidos p
        INNER JOIN clientes c ON p.id_cliente = c.id_cliente
        INNER JOIN productos pr ON p.id_producto = pr.id_producto
        WHERE p.estado_entrega = 'Entregado'
        ORDER BY p.id_pedido DESC";

$resultado = mysqli_query($db, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Pedidos Entregados</title>
</head>
<body>

    <!-- INCLUSIÓN DE LA ESTRUCTURA PERSISTENTE -->
    <?php 
    require_once __DIR__ . '/../layout/header.php'; 
    require_once __DIR__ . '/../layout/nav.php'; 
    ?>

    <h2>Historial de Pedidos Entregados (Concluidos)</h2>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Cliente Destinatario</th>
                <th>Zona / Ciudad</th>
                <th>Dirección de Entrega</th>
                <th>Insumo Agrícola</th>
                <th>Cantidad</th>
                <th>Total Pagado (Bs.)</th>
                <th>Fecha Registro</th>
                <th>Estado</th>
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
                        <td><?php echo htmlspecialchars($row['zona'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['direccion'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['producto'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['cantidad'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars(number_format($row['total_pagar'], 2), ENT_QUOTES, 'UTF-8'); ?> Bs.</td>
                        <td><?php echo htmlspecialchars($row['fecha_registro'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><span style="background-color: #d4edda; color: #155724; padding: 2px 5px; font-weight: bold;">✓ Entregado</span></td>
                    </tr>
                <?php 
                } 
            } else {
                echo "<tr><td colspan='9'>Aún no se han registrado entregas concluidas.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- INCLUSIÓN DEL CIERRE -->
    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>