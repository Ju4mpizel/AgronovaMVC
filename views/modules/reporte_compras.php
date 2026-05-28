<?php
session_start();

// CÓDIGO GUARDIÁN ESTRICTO PARA EL ROL DE GERENTE - INTACTO
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

    <!-- INCLUSIÓN DE LA ESTRUCTURA PERSISTENTE DEL DASHBOARD -->
    <?php 
    require_once __DIR__ . '/../layout/header.php'; 
    require_once __DIR__ . '/../layout/nav.php'; 
    ?>

    <!-- Encabezado del Módulo Gerencial -->
    <div class="flex flex-col gap-1 pb-2 border-b border-slate-100">
        <h2 class="text-2xl font-bold tracking-tight text-slate-800">Reporte de Abastecimiento e Inversión</h2>
        <p class="text-sm text-slate-400 font-medium">Auditoría financiera de capital invertido en la adquisición de insumos agrícolas.</p>
    </div>

    <!-- Distribución de dos columnas Estilo Saas Mockup -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mt-4 items-start">
        
        <!-- Columna Izquierda: Historial de Transacciones (Ocupa 3/4) -->
        <div class="lg:col-span-3 space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Bitácora de Adquisiciones</h3>
            
            <?php if (!empty($reporte)): ?>
                <?php foreach ($reporte as $compra): ?>
                    
                    <!-- Tarjeta de Compra Redondeada -->
                    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-5 hover:border-slate-200 transition-all">
                        <div class="flex justify-between items-center border-b border-slate-50 pb-3 mb-3">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-50 text-slate-700 font-mono text-xs font-bold">
                                <i data-lucide="hash" class="w-3 h-3 text-slate-400"></i> Lote Compra #<?php echo htmlspecialchars($compra['id_compra'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                            <span class="text-xs font-mono text-slate-400 flex items-center gap-1">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i> <?php echo htmlspecialchars($compra['fecha_compra'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </div>
                        
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h4 class="text-base font-bold text-slate-800 flex items-center gap-2">
                                    <i data-lucide="package" class="w-4 h-4 text-slate-400"></i>
                                    <?php echo htmlspecialchars($compra['producto_nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                </h4>
                                <p class="text-xs text-slate-400 mt-0.5 font-medium">Volumen ingresado a stock: <strong class="text-slate-600"><?php echo htmlspecialchars($compra['cantidad'], ENT_QUOTES, 'UTF-8'); ?> unidades</strong></p>
                            </div>
                            
                            <!-- Importe de Costo Destacado en Bruto -->
                            <div class="text-right px-4 py-2 rounded-xl bg-red-50/50 border border-red-100/50 shrink-0">
                                <span class="block text-[10px] font-bold text-red-400 uppercase tracking-wide">Costo de Egreso</span>
                                <span class="text-base font-bold text-red-600 font-mono">Bs. <?php echo number_format($compra['total_costo'], 2); ?></span>
                            </div>
                        </div>
                    </div>
                    
                <?php endforeach; ?>
            <?php else: ?>
                <div class="p-8 text-center bg-slate-50 border border-slate-100 rounded-2xl text-slate-400 italic text-sm">
                    No se han registrado transacciones de abastecimiento en la base de datos aún.
                </div>
            <?php endif; ?>
        </div>

        <!-- Columna Derecha: Tarjeta Flotante Acumuladora (Ocupa 1/4) -->
        <div class="lg:col-span-1 bg-gradient-to-br from-agro-700 to-agro-900 text-white border border-agro-900 rounded-2xl p-6 shadow-sm sticky top-4">
            <div class="text-center space-y-4">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mx-auto text-agro-100">
                    <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-agro-100 opacity-80">Capital Total Invertido</h4>
                    <p class="text-3xl font-extrabold tracking-tight mt-1 font-mono">Bs. <?php echo number_format($totalInvertido, 2); ?></p>
                </div>
                <hr class="border-white/10">
                <div class="flex justify-between items-center text-xs font-medium text-agro-100">
                    <span>Transacciones totales:</span>
                    <strong class="font-mono bg-white/10 px-2 py-0.5 rounded"><?php echo $cantidadCompras; ?></strong>
                </div>
            </div>
        </div>

    </div>

    <!-- INCLUSIÓN DEL CIERRE DEL CONTENEDOR Y HTML -->
    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>