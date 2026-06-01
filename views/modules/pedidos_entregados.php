<?php
session_start();
// Validacion para que no redirija a cualquier pagina sin estar logueado
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
    <title>AgroNova - Entregas Concluidas</title>
</head>
<body>
    <?php 
    require_once __DIR__ . '/../layout/header.php'; 
    require_once __DIR__ . '/../layout/nav.php'; 
    ?>
    <div class="flex flex-col gap-1 pb-2 border-b border-slate-100">
        <h2 class="text-2xl font-bold tracking-tight text-slate-800">Historial de Entregas Concluidas</h2>
        <p class="text-sm text-slate-400 font-medium">Registro histórico de pedidos entregados con éxito al sector agrícola.</p>
    </div>

    <div class="w-full overflow-x-auto border border-slate-100 rounded-2xl shadow-sm bg-white mt-4">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="py-4 px-6">ID Pedido</th>
                    <th class="py-4 px-6">Cliente Agricultor</th>
                    <th class="py-4 px-6">Zona / Dirección</th>
                    <th class="py-4 px-6">Insumo / Cantidad</th>
                    <th class="py-4 px-6">Monto Cobrado</th>
                    <th class="py-4 px-6 text-center">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-600 font-medium">
                <?php 
                if (mysqli_num_rows($resultado) > 0) {
                    while ($row = mysqli_fetch_array($resultado)) { 
                ?>
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-4 px-6 text-slate-400 font-mono text-xs">#OD-<?php echo htmlspecialchars($row['id_pedido'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="py-4 px-6 text-slate-800 font-semibold"><?php echo htmlspecialchars($row['cliente'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="py-4 px-6 text-xs text-slate-500">
                                <span class="font-bold text-slate-700 block"><?php echo htmlspecialchars($row['zona'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php echo htmlspecialchars($row['direccion'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td class="py-4 px-6 text-slate-700 text-xs">
                                <span class="font-medium block text-sm"><?php echo htmlspecialchars($row['producto'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php echo htmlspecialchars($row['cantidad'], ENT_QUOTES, 'UTF-8'); ?> uds.
                            </td>
                            <td class="py-4 px-6 text-slate-900 font-bold font-mono">Bs. <?php echo htmlspecialchars(number_format($row['total_pagar'], 2), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600 text-xs font-bold">
                                    <i data-lucide="check-circle" class="w-3 h-3"></i> Concluido
                                </span>
                            </td>
                        </tr>
                <?php 
                    } 
                } else {
                    echo "<tr><td colspan='6' class='py-8 px-6 text-center text-sm text-slate-400 italic'>Aún no se han completado despachos logísticos en este periodo.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>