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
    <title>AgroNova - Hojas de Ruta</title>
</head>
<body>

    <!-- INCLUSIÓN DE LA ESTRUCTURA PERSISTENTE -->
    <?php 
    require_once __DIR__ . '/../layout/header.php'; 
    require_once __DIR__ . '/../layout/nav.php'; 
    ?>

    <!-- Encabezado del Módulo -->
    <div class="flex flex-col gap-1 pb-2 border-b border-slate-100">
        <h2 class="text-2xl font-bold tracking-tight text-slate-800">Hojas de Ruta y Entregas</h2>
        <p class="text-sm text-slate-400 font-medium">Panel logístico de monitoreo y despacho para conductores autorizados.</p>
    </div>

    <!-- Tabla de Envíos en curso -->
    <div class="w-full overflow-x-auto border border-slate-100 rounded-2xl shadow-sm bg-white mt-4">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="py-4 px-6">Pedido</th>
                    <th class="py-4 px-6">Cliente Agricultor</th>
                    <th class="py-4 px-6">Zona / Dirección Exacta</th>
                    <th class="py-4 px-6">Insumo / Cantidad</th>
                    <th class="py-4 px-6 text-center">Estado Logístico</th>
                    <th class="py-4 px-6 text-center">Cambiar Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-600 font-medium">
                <?php 
                if (mysqli_num_rows($resultado) > 0) {
                    while ($row = mysqli_fetch_array($resultado)) { 
                        $isPendiente = ($row['estado_entrega'] === 'Pendiente');
                ?>
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-4 px-6 text-slate-400 font-mono text-xs">#OD-<?php echo htmlspecialchars($row['id_pedido'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="py-4 px-6 text-slate-800 font-bold"><?php echo htmlspecialchars($row['cliente'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="py-4 px-6 max-w-xs">
                                <span class="block text-xs font-bold text-agro-700 bg-agro-50 px-2 py-0.5 rounded-md w-max mb-1"><?php echo htmlspecialchars($row['zona'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <span class="text-slate-500 text-xs block truncate"><i data-lucide="map-pin" class="w-3 h-3 inline text-slate-400"></i> <?php echo htmlspecialchars($row['direccion'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </td>
                            <td class="py-4 px-6 text-slate-700">
                                <span class="font-semibold block"><?php echo htmlspecialchars($row['producto'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <span class="text-xs text-slate-400 font-mono"><?php echo htmlspecialchars($row['cantidad'], ENT_QUOTES, 'UTF-8'); ?> unidades</span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <?php if ($isPendiente) { ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-red-50 text-red-600 text-xs font-bold">
                                        Pendiente
                                    </span>
                                <?php } else { ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-600 text-xs font-bold">
                                        En Ruta
                                    </span>
                                <?php } ?>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <form action="../../controllers/RutaController.php?action=actualizar_ruta" method="POST" class="inline-flex items-center gap-2 m-0">
                                    <input type="hidden" name="id_pedido" value="<?php echo $row['id_pedido']; ?>">
                                    <select name="estado_entrega" required class="px-2 py-1.5 text-xs font-medium text-slate-600 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-agro-600">
                                        <option value="">-- Cambiar --</option>
                                        <?php if ($isPendiente) { ?>
                                            <option value="En Ruta">En Ruta</option>
                                        <?php } ?>
                                        <option value="Entregado">✓ Entregado</option>
                                    </select>
                                    <button type="submit" class="p-1.5 bg-agro-600 hover:bg-agro-700 text-white rounded-lg transition-all shadow-sm">
                                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                <?php 
                    } 
                } else {
                    echo "<tr><td colspan='6' class='py-8 px-6 text-center text-sm text-slate-400 italic'>No tienes rutas de entrega pendientes asignadas por el momento.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- INCLUSIÓN DEL CIERRE -->
    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>