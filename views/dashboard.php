<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$nombreEmpleado = $_SESSION['usuario_nombre'];
$rolEmpleado = $_SESSION['usuario_rol'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Dashboard</title>
</head>
<body>
    <header>
        <h1>AgroNova Distribuciones S.R.L.</h1>
        <p>Empleado: <?php echo $nombreEmpleado; ?> | Rol: <?php echo $rolEmpleado; ?></p>
        <a href="../controllers/AuthController.php?action=logout">Cerrar Sesión</a>
    </header>
    <hr>
    <nav>
        <h2>Menú</h2>
        <ul>
            <li><a href="dashboard.php">Inicio</a></li>
            <?php
            // GERENTE ADMINISTRADOR (Pestañas de reportes de ventas y compras)
            if ($rolEmpleado == 'gerente') {
                echo '<li><a href="modules/reportes_pedidos.php">Reportes de Pedidos</a></li>';
                echo '<li><a href="modules/reportes_compras.php">Reportes de Compras</a></li>';
            }
            
            // ATENCIÓN Y VENTA (Ventas o Gerente)
            if ($rolEmpleado == 'ventas' || $rolEmpleado == 'gerente') {
                echo '<li><a href="modules/clientes.php">Registrar Nuevo Cliente</a></li>';
                echo '<li><a href="modules/pedidos.php">Registrar Pedido</a></li>';
            }
            
            // ENCARGADO DE ALMACÉN (Almacen o Gerente - Ahora incluye la pestaña de compras)
            if ($rolEmpleado == 'almacen' || $rolEmpleado == 'gerente') {
                echo '<li><a href="modules/inventario.php">CRUD Gestión de Inventario</a></li>';
                echo '<li><a href="modules/compras.php">Registrar Compra de Insumos</a></li>';
            }
            
            // CHOFER REPARTIDOR (Chofer o Gerente)
            if ($rolEmpleado == 'chofer' || $rolEmpleado == 'gerente') {
                echo '<li><a href="modules/rutas.php">Hojas de Ruta / Entregas</a></li>';
            }
            ?>
        </ul>
    </nav>
    <hr>
    <main>
        <h2>Panel de Control Principal</h2>
        <p>Bienvenido al sistema. Has ingresado correctamente.</p>
    </main>
</body>
</html>