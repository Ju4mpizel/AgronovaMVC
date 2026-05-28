<?php
session_start();

// CÓDIGO GUARDIÁN ESTRICTO PARA EL ROL DE GERENTE
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'gerente') {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../controllers/CompraController.php';

$controller = new CompraController();
$reporte = $controller->mostrarReporte();

// Lógica lineal en bruto para calcular los totales de abastecimiento
$totalInvertido = 0;
$cantidadCompras = 0;
if (!empty($reporte)) {
    $cantidadCompras = count($reporte);
    foreach ($reporte as $compra) {
        $totalInvertido += $compra['total_costo'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Reporte de Compras</title>
</head>
<body>

    <?php 
    require_once __DIR__ . '/../layout/header.php'; 
    require_once __DIR__ . '/../layout/nav.php'; 
    ?>

    <h2>Historial General de Abastecimiento (Compras)</h2>

    <table border="0" width="100%" cellpadding="10" cellspacing="0">
        <tr valign="top">
            
            <td width="75%">
                <h3>Lista de Compras Realizadas</h3>
                
                <?php if (!empty($reporte)): ?>
                    <?php foreach ($reporte as $compra): ?>
                        
                        <table border="1" width="100%" cellpadding="10" cellspacing="0" bordercolor="#cccccc">
                            <tr bgcolor="#f9f9f9">
                                <td><strong>Compra de Abastecimiento #<?php echo htmlspecialchars($compra['id_compra'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                <td align="right">Fecha: <?php echo htmlspecialchars($compra['fecha_compra'], ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <strong>Insumo / Producto ingresado:</strong> <?php echo htmlspecialchars($compra['producto_nombre'], ENT_QUOTES, 'UTF-8'); ?><br>
                                    <strong>Cantidad Recibida:</strong> <?php echo htmlspecialchars($compra['cantidad'], ENT_QUOTES, 'UTF-8'); ?> unidades
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="right" bgcolor="#fff5f5">
                                    <font size="+1"><strong>Costo Total: Bs. <?php echo number_format($compra['total_costo'], 2); ?></strong></font>
                                </td>
                            </tr>
                        </table>
                        <br> 
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No se han registrado compras de abastecimiento aún.</p>
                <?php endif; ?>
            </td>

            <td width="25%">
                
                <table border="2" width="100%" cellpadding="15" cellspacing="0" bgcolor="#fff5f5">
                    <tr>
                        <td align="center">
                            <font size="+1"><strong>Inversión Total</strong></font>
                            <hr>
                            <p>Total Capital Invertido</p>
                            <p><font size="+3"><strong>Bs. <?php echo number_format($totalInvertido, 2); ?></strong></font></p>
                            <hr>
                            <p>Transacciones: <strong><?php echo $cantidadCompras; ?></strong></p>
                        </td>
                    </tr>
                </table>

            </td>

        </tr>
    </table>

    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>