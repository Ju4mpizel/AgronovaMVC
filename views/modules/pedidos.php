<?php
// Validacion para que no redirija a cualquier pagina sin estar logueado
session_start();
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
<body class="bg-[#f4f6f8]">
    <?php 
    require_once __DIR__ . '/../layout/header.php'; 
    require_once __DIR__ . '/../layout/nav.php'; 
    ?>
    <div class="flex flex-col gap-1 pb-2 border-b border-slate-100">
        <h2 class="text-2xl font-bold tracking-tight text-slate-800">Registrar Pedido (Venta Directa)</h2>
        <p class="text-sm text-slate-400 font-medium">Despacha insumos agrícolas del inventario cargando la transacción a una cuenta de cliente activa.</p>
    </div>
    <div class="max-w-2xl mt-4">
        <?php if (isset($_GET['error']) && $_GET['error'] === 'stock_insuficiente') { 
            $disp = intval($_GET['disponible'] ?? 0);
        ?>
            <div class="mb-4 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-start gap-3 text-xs text-red-700 font-medium transition-all">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-red-500 shrink-0 mt-0.5"></i>
                <div>
                    <span class="font-bold block text-sm mb-0.5">¡Transacción Reclamada!</span>
                    No se puede procesar el pedido debido a que la cantidad solicitada supera las existencias físicas del almacén. El stock disponible actual de este insumo es de <strong class="underline"><?php echo $disp; ?> unidades</strong>.
                </div>
            </div>
        <?php } ?>
        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-8">
            <form action="../../controllers/PedidosController.php?action=vender" method="POST" class="space-y-5">
                <div class="flex flex-col gap-1.5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">
                            Seleccionar Cliente Destinatario <span class="text-red-500">*</span>
                        </label>
                        <div class="relative w-full sm:w-64">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                            </span>
                            <input type="text" id="buscar_cliente" onkeyup="filtrarClientes()" placeholder="Escribe para buscar cliente..." 
                                   class="w-full pl-9 pr-3 py-1 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:border-agro-600 focus:bg-white transition-all">
                        </div>
                    </div>
                    
                    <div class="relative mt-1">
                        <select name="id_cliente" id="select_cliente" required 
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

    <?php require_once __DIR__ . '/../layout/footer.php'; ?>

    <!--implementacion de script de busqueda, realiza la busqueda mediante un onkeyup -->
    <script>
        function filtrarClientes() {
            const input = document.getElementById('buscar_cliente');
            const filter = input.value.toLowerCase();
            const select = document.getElementById('select_cliente');
            const options = select.options;
            for (let i = 1; i < options.length; i++) {
                const text = options[i].text.toLowerCase();
                if (text.includes(filter)) {
                    options[i].style.display = "";
                } else {
                    options[i].style.display = "none";
                }
            }
        }
    </script>

    <!-- funcion de impresion en pdf este llama a la ventana de imprimir factura que tiene la funcion de windows paint-->
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