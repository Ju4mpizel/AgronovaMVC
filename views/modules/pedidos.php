<?php
session_start();

// CÓDIGO GUARDIÁN DE SEGURIDAD - INTACTO
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] !== 'ventas' && $_SESSION['usuario_rol'] !== 'gerente')) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/conexion.php';
$db = Conexion::conectar();

$clientes = mysqli_query($db, "SELECT id_cliente, nombre_completo, ci_nit FROM clientes");
$productos = mysqli_query($db, "SELECT id_producto, nombre_insumo, stock_disponible FROM productos");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Registrar Pedido</title>
</head>
<body>

    <!-- INCLUSIÓN DE LA ESTRUCTURA PERSISTENTE -->
    <?php 
    require_once __DIR__ . '/../layout/header.php'; 
    require_once __DIR__ . '/../layout/nav.php'; 
    ?>

    <!-- Encabezado del Módulo -->
    <div class="flex flex-col gap-1 pb-2 border-b border-slate-100">
        <h2 class="text-2xl font-bold tracking-tight text-slate-800">Registrar Pedido (Venta Directa)</h2>
        <p class="text-sm text-slate-400 font-medium">Despacha insumos agrícolas del inventario cargando la transacción a una cuenta de cliente activa.</p>
    </div>

    <!-- CONTENEDOR ANCHO ASOCIADO CON AVISOS CONTROLADOS -->
    <div class="max-w-2xl mt-4">

        <!-- ALERTA DE STOCK INSUFICIENTE DINÁMICA -->
        <?php if (isset($_GET['error']) && $_GET['error'] === 'stock_insuficiente') { 
            $disp = intval($_GET['disponible'] ?? 0);
        ?>
            <div class="mb-4 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-start gap-3 text-xs text-red-700 font-medium transition-all">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-red-500 shrink-0 mt-0.5"></i>
                <div>
                    <span class="font-bold block text-sm mb-0.5">¡Transacción Rechazada!</span>
                    No se puede procesar el pedido debido a que la cantidad solicitada supera las existencias físicas del almacén. El stock disponible actual de este insumo es de <strong class="underline"><?php echo $disp; ?> unidades</strong>.
                </div>
            </div>
        <?php } ?>

        <!-- Formulario Transaccional -->
        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-8">
            <form action="../../controllers/PedidosController.php?action=vender" method="POST" class="space-y-5">
                
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Seleccionar Cliente Destinatario <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="id_cliente" required 
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 bg-white focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all appearance-none">
                            <option value="">-- Seleccione un agricultor o empresa registrado --</option>
                            <?php while($c = mysqli_fetch_array($clientes)) { ?>
                                <option value="<?php echo $c['id_cliente']; ?>">
                                    <?php echo htmlspecialchars($c['nombre_completo'] . " (NIT/CI: " . $c['ci_nit'] . ")", ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php } ?>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Seleccionar Insumo del Inventario <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="id_producto" required 
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 bg-white focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all appearance-none">
                            <option value="">-- Seleccione el producto a vender --</option>
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
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Cantidad a Vender <span class="text-red-500">*</span></label>
                        <input type="number" name="cantidad" min="1" placeholder="Ej. 10" required 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Fecha de Registro Oficial</label>
                        <input type="text" name="fecha_registro" value="<?php echo date('Y-m-d H:i'); ?>" required 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-mono text-slate-500 bg-slate-50 cursor-not-allowed focus:outline-none">
                    </div>
                </div>

                <!-- BOTONES DE ACCIÓN CONFIGURADOS -->
                <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <span class="text-xs font-medium text-slate-400 flex items-center gap-1.5">
                        <i data-lucide="shield-check" class="w-4 h-4 text-emerald-500"></i> Stock validado automáticamente.
                    </span>
                    
                    <div class="flex flex-wrap gap-3 w-full sm:w-auto justify-end">
                        <button type="submit" name="solo_guardar" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all shadow-sm">
                            Procesar Venta
                        </button>

                        <button type="submit" name="imprimir_factura" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-agro-600 to-agro-700 text-white text-xs font-bold rounded-xl shadow-sm hover:from-agro-700 hover:to-agro-900 transition-all">
                            <i data-lucide="printer" class="w-4 h-4"></i> Despachar e Imprimir Factura
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- INCLUSIÓN DEL CIERRE -->
    <?php require_once __DIR__ . '/../layout/footer.php'; ?>

    <!-- SCRIPT DISPARADOR DE LA VENTANA DE IMPRESIÓN PDF -->
    <?php if (isset($_GET['imprimir_id'])) { 
        $id_print = intval($_GET['imprimir_id']);
    ?>
        <script>
            var urlFactura = 'imprimir_factura.php?id=' + <?php echo $id_print; ?>;
            var ventana = window.open(urlFactura, 'Factura AgroNova', 'width=800,height=700,resizable=yes,scrollbars=yes');
            ventana.focus();
        </script>
    <?php } ?>

</body>
</html>