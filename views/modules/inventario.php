<?php
session_start();

// CÓDIGO GUARDIÁN DE SEGURIDAD - INTACTO
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] !== 'almacen' && $_SESSION['usuario_rol'] !== 'gerente')) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../models/Producto.php';

$modeloProducto = new Producto();
$resultado = $modeloProducto->listarTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Gestión de Inventario</title>
</head>
<body>

    <!-- INCLUSIÓN DE LA ESTRUCTURA PERSISTENTE -->
    <?php 
    require_once __DIR__ . '/../layout/header.php'; 
    require_once __DIR__ . '/../layout/nav.php'; 
    ?>

    <!-- Encabezado con Botón Catálogo -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-2 border-b border-slate-100">
        <div class="flex flex-col gap-1">
            <h2 class="text-2xl font-bold tracking-tight text-slate-800">Control de Inventario</h2>
            <p class="text-sm text-slate-400 font-medium">Monitorea existencias de insumos agrícolas, precios de mercado y fechas de caducidad.</p>
        </div>
        <a href="nuevo_producto.php" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-agro-600 hover:bg-agro-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all shrink-0">
            <i data-lucide="plus-circle" class="w-4 h-4"></i> Registrar nuevo insumo
        </a>
    </div>

    <!-- Tabla Estilizada Card -->
    <div class="w-full overflow-x-auto border border-slate-100 rounded-2xl shadow-sm bg-white mt-4">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="py-4 px-6">ID</th>
                    <th class="py-4 px-6">Insumo Agrícola</th>
                    <th class="py-4 px-6">Categoría</th>
                    <th class="py-4 px-6">Costo Compra</th>
                    <th class="py-4 px-6">Precio Venta</th>
                    <th class="py-4 px-6 text-center">Stock Existente</th>
                    <th class="py-4 px-6">Vencimiento</th>
                    <th class="py-4 px-6 text-center">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-600 font-medium">
                <?php 
                while ($row = mysqli_fetch_array($resultado)) { 
                    $critico = ($row['stock_disponible'] <= 5);
                ?>
                    <tr class="hover:bg-slate-50/70 transition-colors <?php echo $critico ? 'bg-red-50/30' : ''; ?>">
                        <td class="py-4 px-6 text-slate-400 font-mono text-xs">#PROD-<?php echo htmlspecialchars($row['id_producto'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="py-4 px-6 text-slate-800 font-semibold flex items-center gap-2">
                            <i data-lucide="package" class="w-4 h-4 text-slate-400 shrink-0"></i>
                            <?php echo htmlspecialchars($row['nombre_insumo'], ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-xs">
                                <?php echo htmlspecialchars($row['categoria'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </td>
                        <td class="py-4 px-6 font-mono text-xs text-slate-500">Bs. <?php echo htmlspecialchars(number_format($row['precio_compra'], 2), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="py-4 px-6 font-mono text-sm text-slate-800 font-bold">Bs. <?php echo htmlspecialchars(number_format($row['precio_venta'], 2), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="py-4 px-6 text-center">
                            <?php if ($critico) { ?>
                                <span class="inline-flex items-center justify-center min-w-[60px] px-2.5 py-1 rounded-lg bg-red-100 text-red-700 text-xs font-bold border border-red-200">
                                    <?php echo htmlspecialchars($row['stock_disponible'], ENT_QUOTES, 'UTF-8'); ?> (CRÍTICO)
                                </span>
                            <?php } else { ?>
                                <span class="inline-flex items-center justify-center min-w-[40px] px-2.5 py-1 rounded-lg bg-agro-50 text-agro-700 text-xs font-bold">
                                    <?php echo htmlspecialchars($row['stock_disponible'], ENT_QUOTES, 'UTF-8'); ?> uds.
                                </span>
                            <?php } ?>
                        </td>
                        <td class="py-4 px-6 font-mono text-xs <?php echo $critico ? 'text-red-500 font-medium' : 'text-slate-400'; ?>">
                            <?php echo htmlspecialchars($row['fecha_vencimiento'], ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <a href="editar_producto.php?id=<?php echo urlencode($row['id_producto']); ?>" class="inline-flex items-center gap-1 text-xs font-bold text-agro-600 hover:text-agro-700 hover:underline transition-all">
                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Editar
                            </a>
                        </td>
                    </tr>
                <?php 
                } 
                ?>
            </tbody>
        </table>
    </div>

    <!-- INCLUSIÓN DEL CIERRE -->
    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>