<?php
session_start();

// CÓDIGO GUARDIÁN DE SEGURIDAD - INTACTO
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

    <!-- INCLUSIÓN DE LA ESTRUCTURA PERSISTENTE -->
    <?php 
    require_once __DIR__ . '/../layout/header.php'; 
    require_once __DIR__ . '/../layout/nav.php'; 
    ?>

    <h2>Hojas de Ruta y Control de Entregas (Módulo Chofer)</h2>

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
                        <td><?php echo htmlspecialchars($row['id_pedido'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><strong><?php echo htmlspecialchars($row['cliente'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['zona'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><u><?php echo htmlspecialchars($row['direccion'], ENT_QUOTES, 'UTF-8'); ?></u></td>
                        <td><?php echo htmlspecialchars(!empty($row['telefono']) ? $row['telefono'] : 'Sin teléfono', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['producto'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['cantidad'], ENT_QUOTES, 'UTF-8'); ?></td>
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
                echo "<tr><td colspan='9'>No tienes entregas pendientes ni rutas activas por el momento.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- INCLUSIÓN DEL CIERRE -->
    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>