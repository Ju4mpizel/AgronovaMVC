<?php
session_start();
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] !== 'almacen' && $_SESSION['usuario_rol'] !== 'gerente')) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/conexion.php';
$db = Conexion::conectar();

$id = intval($_GET['id'] ?? 0);
$sql = "SELECT * FROM productos WHERE id_producto = $id LIMIT 1";
$resultado = mysqli_query($db, $sql);
$producto = mysqli_fetch_array($resultado);

if (!$producto) {
    die("Error: Producto no encontrado en el almacén.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Editar Producto</title>
</head>
<body>

    <?php 
    require_once __DIR__ . '/../layout/header.php'; 
    require_once __DIR__ . '/../layout/nav.php'; 
    ?>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-2 border-b border-slate-100">
        <div class="flex flex-col gap-1">
            <h2 class="text-2xl font-bold tracking-tight text-slate-800">Modificar Ficha de Insumo</h2>
            <p class="text-sm text-slate-400 font-medium">Actualiza precios, lotes de existencias o la vigencia del insumo seleccionado.</p>
        </div>
        <a href="inventario.php" class="inline-flex items-center justify-center gap-2 px-3.5 py-2 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold rounded-xl shadow-sm transition-all shrink-0">
            <i data-lucide="x" class="w-3.5 h-3.5"></i> Cancelar cambios
        </a>
    </div>
    <div class="max-w-2xl bg-white border border-slate-100 rounded-2xl shadow-sm p-8 mt-4">
        <form action="../../controllers/InventarioController.php?action=editar" method="POST" class="space-y-5">
            <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($producto['id_producto'], ENT_QUOTES, 'UTF-8'); ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Nombre del Insumo / Producto <span class="text-red-500">*</span></label>
                    <input type="text" name="nombre_insumo" value="<?php echo htmlspecialchars($producto['nombre_insumo'], ENT_QUOTES, 'UTF-8'); ?>" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Categoría del Producto <span class="text-red-500">*</span></label>
                    <input type="text" name="categoria" value="<?php echo htmlspecialchars($producto['categoria'], ENT_QUOTES, 'UTF-8'); ?>" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Costo Compra (Bs.) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="precio_compra" value="<?php echo htmlspecialchars($producto['precio_compra'], ENT_QUOTES, 'UTF-8'); ?>" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Precio Venta (Bs.) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="precio_venta" value="<?php echo htmlspecialchars($producto['precio_venta'], ENT_QUOTES, 'UTF-8'); ?>" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Stock Disponible <span class="text-red-500">*</span></label>
                    <input type="number" name="stock_disponible" value="<?php echo htmlspecialchars($producto['stock_disponible'], ENT_QUOTES, 'UTF-8'); ?>" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
                </div>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Fecha de Vencimiento <span class="text-red-500">*</span></label>
                <input type="text" name="fecha_vencimiento" value="<?php echo htmlspecialchars($producto['fecha_vencimiento'], ENT_QUOTES, 'UTF-8'); ?>" required 
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-mono text-slate-700 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
            </div>

            <div class="pt-2 border-t border-slate-100 flex justify-end">
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-agro-600 hover:bg-agro-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i> Actualizar Producto
                </button>
            </div>
        </form>
    </div>

    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>