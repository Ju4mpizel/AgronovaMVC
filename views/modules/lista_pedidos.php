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

    <!-- Encabezado del Módulo -->
    <div class="flex flex-col gap-1 pb-2 border-b border-slate-100">
        <h2 class="text-2xl font-bold tracking-tight text-slate-800">Historial General de Pedidos</h2>
        <p class="text-sm text-slate-400 font-medium">Cronología completa de solicitudes comerciales de insumos y estados de despacho.</p>
    </div>

    <!-- Contenedor de Tabla con Diseño Redondeado -->
    <div class="w-full overflow-x-auto border border-slate-100 rounded-2xl shadow-sm bg-white mt-4">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="py-4 px-6">ID Pedido</th>
                    <th class="py-4 px-6">Cliente Agricultor</th>
                    <th class="py-4 px-6">Insumo Agrícola</th>
                    <th class="py-4 px-6 text-center">Cantidad</th>
                    <th class="py-4 px-6">Total a Pagar</th>
                    <th class="py-4 px-6">Estado de Entrega</th>
                    <th class="py-4 px-6">Fecha Registro</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-600 font-medium">
                <?php 
                if (mysqli_num_rows($resultado) > 0) {
                    while ($row = mysqli_fetch_array($resultado)) { 
                        $estado = $row['estado_entrega'];
                ?>
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-4 px-6 text-slate-400 font-mono text-xs">#OD-<?php echo htmlspecialchars($row['id_pedido'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="py-4 px-6 text-slate-800 font-semibold"><?php echo htmlspecialchars($row['cliente'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="py-4 px-6"><?php echo htmlspecialchars($row['producto'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="py-4 px-6 text-center font-mono"><?php echo htmlspecialchars($row['cantidad'], ENT_QUOTES, 'UTF-8'); ?> U.</td>
                            <td class="py-4 px-6 text-slate-900 font-bold">Bs. <?php echo htmlspecialchars(number_format($row['total_pagar'], 2), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="py-4 px-6">
                                <?php if ($estado === 'Pendiente') { ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-red-50 text-red-600 text-xs font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> Pendiente
                                    </span>
                                <?php } elseif ($estado === 'En Ruta') { ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-600 text-xs font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> En Ruta
                                    </span>
                                <?php } else { ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600 text-xs font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Entregado
                                    </span>
                                <?php } ?>
                            </td>
                            <td class="py-4 px-6 font-mono text-xs text-slate-400"><?php echo htmlspecialchars($row['fecha_registro'], ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                <?php 
                    } 
                } else {
                    echo "<tr><td colspan='7' class='py-8 px-6 text-center text-sm text-slate-400 italic'>No se han registrado pedidos en el sistema corporativo.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- INCLUSIÓN DEL CIERRE -->
    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>