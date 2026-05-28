<?php
session_start();

// CONTROL DE SEGURIDAD: Solo entran el Chofer repartidor o el Gerente administrador
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] !== 'chofer' && $_SESSION['usuario_rol'] !== 'gerente')) {
    header("Location: ../login.php");
    exit();
}

// Activar reportes de error obligatorios para el control de la docente
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../config/conexion.php';
$db = Conexion::conectar();

// Consulta SQL en bruto para traer únicamente el historial de lo ya ENTRAGADO
$sql = "SELECT p.id_pedido, c.nombre_completo AS cliente, c.zona, c.direccion,
               pr.nombre_insumo AS producto, p.cantidad, p.total_pagar, p.fecha_registro
        FROM pedidos p
        INNER JOIN clientes c ON p.id_cliente = c.id_cliente
        INNER JOIN productos pr ON p.id_producto = pr.id_producto
        WHERE p.estado_entrega = 'Entregado'
        ORDER BY p.id_pedido DESC"; // Del más reciente al más antiguo

$resultado = mysqli_query($db, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Pedidos Entregados</title>
</head>
<body>

    <p>
        <a href="../dashboard.php">◄ Volver al Dashboard</a> | 
        <a href="rutas.php">Ver Rutas Activas / Pendientes</a>
    </p>

    <h2>Historial de Pedidos Entregados (Concluidos)</h2>
    <p><em>Lista oficial de insumos agrícolas distribuidos y entregados con éxito a los clientes.</em></p>

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
                        <td><?php echo $row['id_pedido']; ?></td>
                        <td><?php echo $row['cliente']; ?></td>
                        <td><?php echo $row['zona']; ?></td>
                        <td><?php echo $row['direccion']; ?></td>
                        <td><?php echo $row['producto']; ?></td>
                        <td><?php echo $row['cantidad']; ?></td>
                        <td><?php echo number_format($row['total_pagar'], 2); ?> Bs.</td>
                        <td><?php echo $row['fecha_registro']; ?></td>
                        <td>
                            <span style="background-color: #d4edda; color: #155724; padding: 2px 5px; font-weight: bold;">✓ Entregado</span>
                        </td>
                    </tr>
                <?php 
                } 
            } else {
                echo "<tr><td colspan='9'>Aún no se han registrado entregas concluidas en el sistema.</td></tr>";
                echo '<li><a href="modules/pedidos_entregados.php">Historial de Pedidos Entregados</a></li>';
            }
            ?>
        </tbody>
    </table>

</body>
</html>