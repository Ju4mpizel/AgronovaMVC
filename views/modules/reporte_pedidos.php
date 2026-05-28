<?php
session_start();

// CÓDIGO GUARDIÁN ESTRICTO PARA EL ROL DE GERENTE - INTACTO
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

    <!-- INCLUSIÓN DE LA ESTRUCTURA PERSISTENTE DEL DASHBOARD -->
    <?php 
    require_once __DIR__ . '/../layout/header.php'; 
    require_once __DIR__ . '/../layout/nav.php'; 
    ?>

    <!-- Encabezado del Módulo Gerencial -->
    <div class="flex flex-col gap-1 pb-2 border-b border-slate-100">
        <h2 class="text-2xl font-bold tracking-tight text-slate-800">Reporte de Pedidos y Ventas Totales</h2>
        <p class="text-sm text-slate-400 font-medium">Balance financiero y comercial de solicitudes y despachos facturados.</p>
    </div>

    <!-- Distribución de dos columnas Estilo Saas Mockup -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mt-4 items-start">
        
        <!-- Columna Izquierda: Historial de Ventas (Ocupa 3/4) -->
        <div class="lg:col-span-3 space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Listado Cronológico de Órdenes</h3>
            
            <?php if (!empty($reporte)): ?>
                <?php foreach ($reporte as $pedido): ?>
                    <?php $estado = $pedido['estado_entrega']; ?>
                    
                    <!-- Tarjeta de Pedido Redondeada -->
                    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-5 hover:border-slate-200 transition-all">
                        <div class="flex justify-between items-center border-b border-slate-50 pb-3 mb-3">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-50 text-slate-700 font-mono text-xs font-bold">
                                <i data-lucide="shopping-bag" class="w-3 h-3 text-slate-400"></i> Pedido #OD-<?php echo htmlspecialchars($pedido['id_pedido'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                            
                            <!-- Badges de Estado Idénticos a la Lista de Pedidos -->
                            <div>
                                <?php if ($estado === 'Pendiente') { ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-red-50 text-red-600 text-xs font-bold">
                                        Pendiente
                                    </span>
                                <?php } elseif ($estado === 'En Ruta') { ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-amber-50 text-amber-600 text-xs font-bold">
                                        En Ruta
                                    </span>
                                <?php } else { ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-emerald-50 text-emerald-600 text-xs font-bold">
                                        Entregado
                                    </span>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="space-y-1">
                                <h4 class="text-sm font-medium text-slate-400">
                                    Cliente Comprador: <strong class="text-slate-700 font-semibold"><?php echo htmlspecialchars($pedido['cliente_nombre'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                </h4>
                                <p class="text-sm text-slate-700 font-semibold flex items-center gap-1.5">
                                    <i data-lucide="sprout" class="w-4 h-4 text-slate-400"></i>
                                    <?php echo htmlspecialchars($pedido['producto_nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                    <span class="font-mono text-xs text-slate-400 font-normal">(x<?php echo htmlspecialchars($pedido['cantidad'], ENT_QUOTES, 'UTF-8'); ?> unidades)</span>
                                </p>
                                <span class="block font-mono text-[10px] text-slate-400">Fecha Registro: <?php echo htmlspecialchars($pedido['fecha_registro'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>
                            
                            <!-- Monto de Venta Destacado -->
                            <div class="text-right px-4 py-2 rounded-xl bg-emerald-50/50 border border-emerald-100/50 shrink-0">
                                <span class="block text-[10px] font-bold text-emerald-500 uppercase tracking-wide">Ingreso Bruto</span>
                                <span class="text-base font-bold text-emerald-600 font-mono">Bs. <?php echo number_format($pedido['total_pagar'], 2); ?></span>
                            </div>
                        </div>
                    </div>
                    
                <?php endforeach; ?>
            <?php else: ?>
                <div class="p-8 text-center bg-slate-50 border border-slate-100 rounded-2xl text-slate-400 italic text-sm">
                    No se encontraron órdenes de pedidos registradas en el sistema.
                </div>
            <?php endif; ?>
        </div>

        <!-- Columna Derecha: Tarjeta Flotante Acumuladora de Ingresos -->
        <div class="lg:col-span-1 bg-gradient-to-br from-slate-800 to-slate-950 text-white border border-slate-900 rounded-2xl p-6 shadow-sm sticky top-4">
            <div class="text-center space-y-4">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mx-auto text-emerald-400">
                    <i data-lucide="trending-up" class="w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Ingresos Totales Acumulados</h4>
                    <p class="text-3xl font-extrabold tracking-tight mt-1 font-mono text-emerald-400">Bs. <?php echo number_format($totalGeneral, 2); ?></p>
                </div>
                <hr class="border-white/10">
                <div class="flex justify-between items-center text-xs font-medium text-slate-400">
                    <span>Volumen de Órdenes:</span>
                    <strong class="font-mono bg-white/10 text-white px-2 py-0.5 rounded"><?php echo $cantidadPedidos; ?></strong>
                </div>
            </div>
        </div>

    </div>

    <!-- INCLUSIÓN DEL CIERRE DEL CONTENEDOR Y HTML -->
    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>