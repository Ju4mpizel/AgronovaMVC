<?php
session_start();

// CONTROL DE SEGURIDAD: Solo entran el Chofer repartidor o el Gerente administrador
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] !== 'chofer' && $_SESSION['usuario_rol'] !== 'gerente')) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../models/Ruta.php';

$modeloRuta = new Ruta();
$resultado = $modeloRuta->listarPedidosParaEntrega();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Hojas de Ruta / Entregas</title>
</head>
<body>

    <p>
        <a href="../dashboard.php">◄ Volver al Dashboard</a>
    </p>

    <h2>Hojas de Ruta y Control de Entregas (Módulo Chofer)</h2>
    <p><em>Aquí se listan los pedidos pendientes de envío o que actualmente están en camino al destino.</em></p>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Cliente Destinatario</th>
                <th>Zona / Ciudad</th>
                <th>Dirección Exacta</th>
                <th>Teléfono Contacto</th>
                <th>Insumo Agrícola</th>
                <th>Cantidad</th>
                <th>Estado Actual</th>
                <th>Acción / Cambiar Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (mysqli_num_rows($resultado) > 0) {
                while ($row = mysqli_fetch_array($resultado)) { 
                ?>
                    <tr>
                        <td><?php echo $row['id_pedido']; ?></td>
                        <td><strong><?php echo $row['cliente']; ?></strong></td>
                        <td><?php echo $row['zona']; ?></td>
                        <td><u><?php echo $row['direccion']; ?></u></td>
                        <td><?php echo !empty($row['telefono']) ? $row['telefono'] : 'Sin teléfono'; ?></td>
                        <td><?php echo $row['producto']; ?></td>
                        <td><?php echo $row['cantidad']; ?></td>
                        <td>
                            <?php if ($row['estado_entrega'] === 'Pendiente') { ?>
                                <span style="background-color: #ffcccc; padding: 2px 5px;">Pendiente</span>
                            <?php } else { ?>
                                <span style="background-color: #fff3cd; padding: 2px 5px;">En Ruta</span>
                            <?php } ?>
                        </td>
                        <td>
                            <form action="../../controllers/RutaController.php?action=actualizar_ruta" method="POST" style="margin:0;">
                                <input type="hidden" name="id_pedido" value="<?php echo $row['id_pedido']; ?>">
                                
                                <select name="estado_entrega" required>
                                    <option value="">-- Cambiar a --</option>
                                    <?php if ($row['estado_entrega'] === 'Pendiente') { ?>
                                        <option value="En Ruta">En Ruta</option>
                                    <?php } ?>
                                    <option value="Entregado">Entregado</option>
                                </select>
                                
                                <button type="submit">Guardar</button>
                            </form>
                        </td>
                    </tr>
                <?php 
                } 
            } else {
                echo "<tr><td colspan='9'>No tienes entregas pendientes ni rutas activas por el momento. ¡Buen trabajo!</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>