<?php
// views/layout/nav.php
$rolEmpleado = $_SESSION['usuario_rol'] ?? '';
?>
<nav>
    <strong>Menú de Operaciones Persistente:</strong>
    <ul>
        <li><a href="/AgronovaMVC/views/dashboard.php">Inicio</a></li>
        
        <?php if ($rolEmpleado === 'gerente') { ?>
            <li><a href="/AgronovaMVC/views/modules/reporte_pedidos.php">Reportes de Pedidos</a></li>
            <li><a href="/AgronovaMVC/views/modules/reporte_compras.php">Reportes de Compras</a></li>
        <?php } ?>
        
        <?php if ($rolEmpleado === 'ventas' || $rolEmpleado === 'gerente') { ?>
            <li><a href="/AgronovaMVC/views/modules/clientes.php">Gestión de Clientes</a></li>
            <li><a href="/AgronovaMVC/views/modules/pedidos.php">Registrar Pedido (Venta)</a></li>
            <li><a href="/AgronovaMVC/views/modules/lista_pedidos.php">Historial de Pedidos</a></li>
        <?php } ?>
        
        <?php if ($rolEmpleado === 'almacen' || $rolEmpleado === 'gerente') { ?>
            <li><a href="/AgronovaMVC/views/modules/inventario.php">Gestión de Inventario</a></li>
            <li><a href="/AgronovaMVC/views/modules/compras.php">Registrar Compra de Insumos</a></li>
        <?php } ?>
        
        <?php if ($rolEmpleado === 'chofer' || $rolEmpleado === 'gerente') { ?>
            <li><a href="/AgronovaMVC/views/modules/rutas.php">Hojas de Ruta Activas</a></li>
            <li><a href="/AgronovaMVC/views/modules/pedidos_entregados.php">Historial de Entregas</a></li>
        <?php } ?>
    </ul>
</nav>
<hr>
<main>