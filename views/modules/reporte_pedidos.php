<?php
session_start();

// CÓDIGO GUARDIÁN ESTRICTO PARA EL ROL DE GERENTE
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'gerente') {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../controllers/PedidosController.php';

$controller = new PedidosController();
$reporte = $controller->mostrarReporte();

$totalGeneral = 0;
$cantidadPedidos = 0;
if (!empty($reporte)) {
    $cantidadPedidos = count($reporte);
    foreach ($reporte as $pedido) {
        $totalGeneral += $pedido['total_pagar'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Reporte de Pedidos</title>
</head>
<body>

    <?php 
    require_once __DIR__ . '/../layout/header.php'; 
    require_once __DIR__ . '/../layout/nav.php'; 
    ?>

    <h2>Reporte General de Pedidos</h2>

    <table border="0" width="100%" cellpadding="10" cellspacing="0">
        <tr valign="top">
            
            <td width="75%">
                <h3>Lista de Pedidos</h3>
                
                <?php if (!empty($reporte)): ?>
                    <?php foreach ($reporte as $pedido): ?>
                        
                        <table border="1" width="100%" cellpadding="10" cellspacing="0" bordercolor="#cccccc">
                            <tr bgcolor="#f9f9f9">
                                <td><strong>Pedido #<?php echo htmlspecialchars($pedido['id_pedido'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                <td align="right">Fecha: <?php echo htmlspecialchars($pedido['fecha_registro'], ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <strong>Cliente:</strong> <?php echo htmlspecialchars($pedido['cliente_nombre'], ENT_QUOTES, 'UTF-8'); ?><br>
                                    <strong>Producto:</strong> <?php echo htmlspecialchars($pedido['producto_nombre'], ENT_QUOTES, 'UTF-8'); ?><br>
                                    <strong>Cantidad:</strong> <?php echo htmlspecialchars($pedido['cantidad'], ENT_QUOTES, 'UTF-8'); ?><br>
                                    <strong>Estado:</strong> <u><?php echo htmlspecialchars($pedido['estado_entrega'], ENT_QUOTES, 'UTF-8'); ?></u>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="right" bgcolor="#f4f9f9">
                                    <font size="+1"><strong>Monto: Bs. <?php echo number_format($pedido['total_pagar'], 2); ?></strong></font>
                                </td>
                            </tr>
                        </table>
                        <br> 
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No se encontraron pedidos registrados.</p>
                <?php endif; ?>
            </td>

            <td width="25%">
                
                <table border="2" width="100%" cellpadding="15" cellspacing="0" bgcolor="#f2f7f7">
                    <tr>
                        <td align="center">
                            <font size="+1"><strong>Resumen General</strong></font>
                            <hr>
                            <p>Total de Ventas Acumuladas</p>
                            <p><font size="+3"><strong>Bs. <?php echo number_format($totalGeneral, 2); ?></strong></font></p>
                            <hr>
                            <p>Cantidad de pedidos: <strong><?php echo $cantidadPedidos; ?></strong></p>
                        </td>
                    </tr>
                </table>

            </td>

        </tr>
    </table>

    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>