<?php
session_start();
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] !== 'ventas' && $_SESSION['usuario_rol'] !== 'gerente')) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Registrar Cliente</title>
</head>
<body>
    <?php 
    require_once __DIR__ . '/../layout/header.php'; 
    require_once __DIR__ . '/../layout/nav.php'; 
    ?>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-2 border-b border-slate-100">
        <div class="flex flex-col gap-1">
            <h2 class="text-2xl font-bold tracking-tight text-slate-800">Registrar Nuevo Cliente</h2>
            <p class="text-sm text-slate-400 font-medium">Ingresa los datos del productor agrícola para habilitar transacciones en el sistema.</p>
        </div>
        <a href="clientes.php" class="inline-flex items-center justify-center gap-2 px-3.5 py-2 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold rounded-xl shadow-sm transition-all shrink-0">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Volver a la lista
        </a>
    </div>
    <div class="max-w-2xl bg-white border border-slate-100 rounded-2xl shadow-sm p-8 mt-4">
        <form action="../../controllers/ClienteController.php?action=registrar" method="POST" class="space-y-5">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Nombre Completo / Razón Social <span class="text-red-500">*</span></label>
                    <input type="text" name="nombre_completo" placeholder="Ej. Asociación Agraria Central" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Documento de Identidad / NIT <span class="text-red-500">*</span></label>
                    <input type="text" name="ci_nit" placeholder="Ej. 10203040" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Teléfono / Celular de Contacto</label>
                    <input type="text" name="telefono" placeholder="Ej. 71234567" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Zona / Ciudad Base <span class="text-red-500">*</span></label>
                    <input type="text" name="zona" placeholder="Ej. Cochabamba" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
                </div>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Dirección Exacta de Suministro <span class="text-red-500">*</span></label>
                <input type="text" name="direccion" placeholder="Ej. Av. Blanco Galindo KM 5" required 
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
            </div>

            <div class="pt-2 border-t border-slate-100 flex justify-end">
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-agro-600 hover:bg-agro-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
                    <i data-lucide="save" class="w-4 h-4"></i> Guardar Cliente Activo
                </button>
            </div>
        </form>
    </div>
    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>