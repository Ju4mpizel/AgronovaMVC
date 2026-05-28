<?php
session_start();

// CÓDIGO GUARDIÁN DE SEGURIDAD INTEGRAL - INTACTO
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../config/conexion.php';
$db = Conexion::conectar();

// Extracción de métricas vivas
$resClientes = mysqli_query($db, "SELECT COUNT(*) AS total FROM clientes");
$totalClientes = mysqli_fetch_assoc($resClientes)['total'];

$resProductos = mysqli_query($db, "SELECT COUNT(*) AS total FROM productos");
$totalProductos = mysqli_fetch_assoc($resProductos)['total'];

$resCritico = mysqli_query($db, "SELECT COUNT(*) AS total FROM productos WHERE stock_disponible <= 5");
$totalCritico = mysqli_fetch_assoc($resCritico)['total'];

$resPedidos = mysqli_query($db, "SELECT COUNT(*) AS total FROM pedidos");
$totalPedidos = mysqli_fetch_assoc($resPedidos)['total'];

require_once __DIR__ . '/layout/header.php'; 
require_once __DIR__ . '/layout/nav.php'; 
?>

<!-- Cabecera del Dashboard principal -->
<div class="flex flex-col gap-1">
    <h2 class="text-3xl font-bold tracking-tight text-slate-800">Dashboard</h2>
    <p class="text-sm text-slate-400 font-medium">Planifica, monitorea y administra el abastecimiento agrícola con facilidad.</p>
</div>

<!-- Bloque de Tarjetas de Negocio Redondeadas (Estilo Donezo Mockup) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-2">
    
    <!-- Tarjeta 1: Comercial (Verde Difuminado Suave Premium) -->
    <div class="bg-gradient-to-br from-agro-700 to-agro-900 text-white rounded-2xl p-6 relative overflow-hidden shadow-sm flex flex-col justify-between h-40">
        <div class="flex justify-between items-start">
            <span class="text-sm font-semibold tracking-wide text-agro-100 opacity-90">Clientes Activos</span>
            <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center">
                <i data-lucide="arrow-up-right" class="w-4 h-4 text-white"></i>
            </div>
        </div>
        <div>
            <h3 class="text-4xl font-bold tracking-tight"><?php echo $totalClientes; ?></h3>
            <p class="text-xs text-agro-100/70 mt-1 font-medium">Cartera total de productores agrícolas</p>
        </div>
    </div>

    <!-- Tarjeta 2: Almacén e Inventario (Blanca con detalles redondeados) -->
    <div class="bg-white border border-slate-100 shadow-sm rounded-2xl p-6 relative overflow-hidden flex flex-col justify-between h-40">
        <div class="flex justify-between items-start">
            <span class="text-sm font-semibold tracking-wide text-slate-500">Insumos en Catálogo</span>
            <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center border border-slate-100">
                <i data-lucide="boxes" class="w-4 h-4 text-slate-400"></i>
            </div>
        </div>
        <div>
            <h3 class="text-4xl font-bold tracking-tight text-slate-800"><?php echo $totalProductos; ?></h3>
            <div class="mt-1 flex items-center">
                <?php if ($totalCritico > 0) { ?>
                    <span class="text-xs px-2 py-0.5 rounded-lg bg-red-50 text-red-600 font-semibold flex items-center gap-1">
                        <i data-lucide="alert-triangle" class="w-3 h-3"></i> <?php echo $totalCritico; ?> en stock crítico
                    </span>
                <?php } else { ?>
                    <span class="text-xs px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-600 font-semibold">
                        ✓ Niveles de almacén estables
                    </span>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Tarjeta 3: Operaciones y Ventas -->
    <div class="bg-white border border-slate-100 shadow-sm rounded-2xl p-6 relative overflow-hidden flex flex-col justify-between h-40">
        <div class="flex justify-between items-start">
            <span class="text-sm font-semibold tracking-wide text-slate-500">Pedidos Procesados</span>
            <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center border border-slate-100">
                <i data-lucide="shopping-bag" class="w-4 h-4 text-slate-400"></i>
            </div>
        </div>
        <div>
            <h3 class="text-4xl font-bold tracking-tight text-slate-800"><?php echo $totalPedidos; ?></h3>
            <p class="text-xs text-slate-400 mt-1 font-medium">Historial acumulado de distribución</p>
        </div>
    </div>

</div>

<!-- Mensaje informativo de pie de panel -->
<div class="mt-4 p-5 bg-slate-50 border border-slate-100 rounded-2xl flex items-start gap-4">
    <div class="w-8 h-8 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 shrink-0">
        <i data-lucide="info" class="w-4 h-4"></i>
    </div>
    <div class="text-xs text-slate-500 leading-relaxed">
        <p class="font-semibold text-slate-700 mb-0.5">Indicador Automatizado del Sistema</p>
        Las métricas mostradas reflejan directamente las operaciones de la base de datos de AgroNova. El menú lateral izquierdo se adaptará de forma automática según el nivel de seguridad de tu cuenta.
    </div>
</div>

<?php 
require_once __DIR__ . '/layout/footer.php'; 
?>