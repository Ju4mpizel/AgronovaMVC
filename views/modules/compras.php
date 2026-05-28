<?php
session_start();

// CÓDIGO GUARDIÁN DE SEGURIDAD - INTACTO
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] !== 'almacen' && $_SESSION['usuario_rol'] !== 'gerente')) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/conexion.php';
$db = Conexion::conectar();

$productos = mysqli_query($db, "SELECT id_producto, nombre_insumo, stock_disponible FROM productos");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Registrar Compra</title>
</head>
<body>

    <!-- INCLUSIÓN DE LA ESTRUCTURA PERSISTENTE -->
    <?php 
    require_once __DIR__ . '/../layout/header.php'; 
    require_once __DIR__ . '/../layout/nav.php'; 
    ?>

    <!-- Encabezado -->
    <div class="flex flex-col gap-1 pb-2 border-b border-slate-100">
        <h2 class="text-2xl font-bold tracking-tight text-slate-800">Registrar Compra de Insumos</h2>
        <p class="text-sm text-slate-400 font-medium">Incrementa existencias físicas registrando transacciones de proveedores en bruto.</p>
    </div>

    <!-- Contenedor del Formulario -->
    <div class="max-w-2xl bg-white border border-slate-100 rounded-2xl shadow-sm p-8 mt-4">
        <form action="../../controllers/CompraController.php?action=comprar" method="POST" class="space-y-5">
            
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Seleccionar Insumo para Reabastecer <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select name="id_producto" required 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 bg-white focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all appearance-none">
                        <option value="">-- Seleccione el producto ingresante --</option>
                        <?php while($p = mysqli_fetch_array($productos)) { ?>
                            <option value="<?php echo $p['id_producto']; ?>">
                                <?php echo htmlspecialchars($p['nombre_insumo'] . " (Stock actual: " . $p['stock_disponible'] . " unidades)", ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php } ?>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Cantidad Adquirida <span class="text-red-500">*</span></label>
                    <input type="number" name="cantidad" min="1" placeholder="Ej. 100" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Fecha de Entrada</label>
                    <input type="text" name="fecha_compra" value="<?php echo date('Y-m-d H:i'); ?>" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-mono text-slate-500 bg-slate-50 cursor-not-allowed focus:outline-none">
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex justify-end">
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-agro-600 hover:bg-agro-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
                    <i data-lucide="truck" class="w-4 h-4"></i> Confirmar Entrada de Mercadería
                </button>
            </div>
        </form>
    </div>

    <!-- INCLUSIÓN DEL CIERRE -->
    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>