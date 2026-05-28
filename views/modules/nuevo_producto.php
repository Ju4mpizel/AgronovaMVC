<?php
session_start();

// CÓDIGO GUARDIÁN DE SEGURIDAD - INTACTO
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] !== 'almacen' && $_SESSION['usuario_rol'] !== 'gerente')) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Nuevo Producto</title>
</head>
<body>

    <!-- INCLUSIÓN DE LA ESTRUCTURA PERSISTENTE -->
    <?php 
    require_once __DIR__ . '/../layout/header.php'; 
    require_once __DIR__ . '/../layout/nav.php'; 
    ?>

    <!-- Encabezado con Botón de Retorno -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-2 border-b border-slate-100">
        <div class="flex flex-col gap-1">
            <h2 class="text-2xl font-bold tracking-tight text-slate-800">Registrar Nuevo Insumo</h2>
            <p class="text-sm text-slate-400 font-medium">Introduce un nuevo insumo químico o biológico al catálogo general de la distribuidora.</p>
        </div>
        <a href="inventario.php" class="inline-flex items-center justify-center gap-2 px-3.5 py-2 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold rounded-xl shadow-sm transition-all shrink-0">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Volver al inventario
        </a>
    </div>

    <!-- Contenedor del Formulario -->
    <div class="max-w-2xl bg-white border border-slate-100 rounded-2xl shadow-sm p-8 mt-4">
        <form action="../../controllers/InventarioController.php?action=registrar" method="POST" class="space-y-5">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Nombre del Insumo / Producto <span class="text-red-500">*</span></label>
                    <input type="text" name="nombre_insumo" placeholder="Ej. Urea Granulada 46%" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Categoría del Producto <span class="text-red-500">*</span></label>
                    <input type="text" name="categoria" placeholder="Ej. Fertilizantes" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Precio Compra (Bs.) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="precio_compra" placeholder="Ej. 110.00" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Precio Venta (Bs.) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="precio_venta" placeholder="Ej. 145.50" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Stock Inicial <span class="text-red-500">*</span></label>
                    <input type="number" name="stock_disponible" placeholder="Ej. 20" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
                </div>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Fecha de Vencimiento <span class="text-red-500">*</span></label>
                <input type="text" name="fecha_vencimiento" placeholder="AAAA-MM-DD" value="2027-12-31" required 
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-mono text-slate-700 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
            </div>

            <div class="pt-2 border-t border-slate-100 flex justify-end">
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-agro-600 hover:bg-agro-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
                    <i data-lucide="save" class="w-4 h-4"></i> Catalogar Producto
                </button>
            </div>
        </form>
    </div>

    <!-- INCLUSIÓN DEL CIERRE -->
    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>