<?php
// views/layout/nav.php
$rolEmpleado = $_SESSION['usuario_rol'] ?? '';
$url_base = '/AgronovaMVC/views/modules/';
$url_inicio = '/AgronovaMVC/views/dashboard.php';
?>
<!-- BARRA LATERAL ESTÁTICA: Altura total y scroll vertical propio e independiente si es necesario -->
<aside class="w-72 h-full bg-white rounded-2xl border border-slate-100 flex flex-col justify-between p-6 shadow-sm shrink-0 overflow-y-auto custom-scroll">
    <div>
        <!-- Logo de la Empresa -->
        <div class="flex items-center gap-3 px-2 mb-8">
            <div class="w-10 h-10 rounded-xl bg-agro-100 flex items-center justify-center text-agro-600">
                <i data-lucide="sprout" class="w-6 h-6"></i>
            </div>
            <div>
                <h1 class="font-bold text-lg leading-tight text-slate-800">AgroNova</h1>
                <span class="text-xs text-slate-400 font-medium">Distribuciones S.R.L.</span>
            </div>
        </div>

        <!-- Menú Principal -->
        <div class="space-y-6">
            <div>
                <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase px-2">Menú Principal</span>
                <ul class="mt-2 space-y-1">
                    <li>
                        <a href="<?php echo $url_inicio; ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-agro-600 transition-all">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Secciones por Roles Dinámicos -->
            <?php if ($rolEmpleado === 'ventas' || $rolEmpleado === 'gerente') { ?>
            <div>
                <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase px-2">Comercial</span>
                <ul class="mt-2 space-y-1">
                    <li>
                        <a href="<?php echo $url_base; ?>clientes.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-agro-600 transition-all">
                            <i data-lucide="users" class="w-4 h-4"></i> Gestión Clientes
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $url_base; ?>pedidos.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-agro-600 transition-all">
                            <i data-lucide="shopping-cart" class="w-4 h-4"></i> Registrar Venta
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $url_base; ?>lista_pedidos.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-agro-600 transition-all">
                            <i data-lucide="file-text" class="w-4 h-4"></i> Historial Pedidos
                        </a>
                    </li>
                </ul>
            </div>
            <?php } ?>

            <?php if ($rolEmpleado === 'almacen' || $rolEmpleado === 'gerente') { ?>
            <div>
                <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase px-2">Logística</span>
                <ul class="mt-2 space-y-1">
                    <li>
                        <a href="<?php echo $url_base; ?>inventario.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-agro-600 transition-all">
                            <i data-lucide="boxes" class="w-4 h-4"></i> Gestión Inventario
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $url_base; ?>compras.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-agro-600 transition-all">
                            <i data-lucide="truck" class="w-4 h-4"></i> Registrar Compra
                        </a>
                    </li>
                </ul>
            </div>
            <?php } ?>

            <?php if ($rolEmpleado === 'chofer' || $rolEmpleado === 'gerente') { ?>
            <div>
                <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase px-2">Entregas</span>
                <ul class="mt-2 space-y-1">
                    <li>
                        <a href="<?php echo $url_base; ?>rutas.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-agro-600 transition-all">
                            <i data-lucide="map-pin" class="w-4 h-4"></i> Hojas de Ruta
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $url_base; ?>pedidos_entregados.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-agro-600 transition-all">
                            <i data-lucide="check-circle" class="w-4 h-4"></i> Entregas Concluidas
                        </a>
                    </li>
                </ul>
            </div>
            <?php } ?>

            <?php if ($rolEmpleado === 'gerente') { ?>
            <div>
                <span class="text-[11px] font-bold tracking-wider text-slate-400 uppercase px-2">Reportes Gerenciales</span>
                <ul class="mt-2 space-y-1">
                    <li>
                        <a href="<?php echo $url_base; ?>reporte_pedidos.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-agro-600 transition-all">
                            <i data-lucide="trending-up" class="w-4 h-4"></i> Reporte Ventas
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $url_base; ?>reporte_compras.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-agro-600 transition-all">
                            <i data-lucide="bar-chart-3" class="w-4 h-4"></i> Reporte Compras
                        </a>
                    </li>
                </ul>
            </div>
            <?php } ?>
        </div>
    </div>

    <!-- Sección de Usuario Inferior Fija dentro del aside -->
    <div class="pt-4 mt-6 border-t border-slate-100 flex flex-col gap-3 shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center font-bold text-sm text-slate-700 uppercase">
                <?php echo substr($_SESSION['usuario_nombre'], 0, 2); ?>
            </div>
            <div class="truncate">
                <p class="text-sm font-semibold text-slate-700 truncate"><?php echo htmlspecialchars($_SESSION['usuario_nombre'], ENT_QUOTES, 'UTF-8'); ?></p>
                <span class="text-xs text-slate-400 capitalize"><?php echo htmlspecialchars($rolEmpleado, ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
        </div>
        <a href="/AgronovaMVC/controllers/AuthController.php?action=logout" class="flex items-center justify-center gap-2 w-full py-2 bg-slate-50 hover:bg-red-50 text-slate-500 hover:text-red-600 rounded-xl text-xs font-semibold transition-all">
            <i data-lucide="log-out" class="w-3.5 h-3.5"></i> Cerrar Sesión
        </a>
    </div>
</aside>

<!-- PANEL DERECHO TOTALMENTE INDEPENDIENTE: Altura completa, scroll vertical propio e inmune a deformaciones -->
<main class="flex-1 h-full bg-white rounded-2xl border border-slate-100 p-8 shadow-sm overflow-y-auto min-w-0 flex flex-col gap-6">