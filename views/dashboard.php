<?php
session_start();

// TU CÓDIGO GUARDIÁN ORIGINAL SE QUEDA AQUÍ INTACTO
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// 1. IMPORTAR LA CONEXIÓN DIRECTA PARA LAS MÉTRICAS EN TIEMPO REAL
require_once __DIR__ . '/../config/conexion.php';
$db = Conexion::conectar();

// 2. EXTRACCIÓN DE DATOS DE SESIÓN
$nombreEmpleado = $_SESSION['usuario_nombre'];
$rolEmpleado = $_SESSION['usuario_rol'];
$horaAcceso = date('H:i'); // Captura la hora actual de actividad

// 3. CONSULTAS EN BRUTO PARA GENERAR LAS MÉTRICAS DEL PANEL DE CONTROL
// Contar Clientes Totales
$resClientes = mysqli_query($db, "SELECT COUNT(*) AS total FROM clientes");
$dataClientes = mysqli_fetch_assoc($resClientes);
$totalClientes = $dataClientes['total'];

// Contar Productos Totales y Stock Crítico (Menor o igual a 5 unidades)
$resProductos = mysqli_query($db, "SELECT COUNT(*) AS total FROM productos");
$dataProductos = mysqli_fetch_assoc($resProductos);
$totalProductos = $dataProductos['total'];

$resCritico = mysqli_query($db, "SELECT COUNT(*) AS total FROM productos WHERE stock_disponible <= 5");
$dataCritico = mysqli_fetch_assoc($resCritico);
$totalCritico = $dataCritico['total'];

// Contar Pedidos Totales
$resPedidos = mysqli_query($db, "SELECT COUNT(*) AS total FROM pedidos");
$dataPedidos = mysqli_fetch_assoc($resPedidos);
$totalPedidos = $dataPedidos['total'];
?>
<?php 
// 4. UNIMOS LA CABECERA Y EL MENÚ PERSISTENTE DEL LAYOUT EN BRUTO
require_once __DIR__ . '/layout/header.php'; 
require_once __DIR__ . '/layout/nav.php'; 
?>

<h2>Panel de Control e Información General</h2>
<p>Bienvenido al sistema. Has ingresado de manera exitosa a la plataforma de monitoreo de AgroNova Distribuciones S.R.L.</p>
<hr>

<h3>Ficha de Sesión del Operador</h3>
<ul>
    <li><strong>Nombre del Trabajador:</strong> <?php echo htmlspecialchars($nombreEmpleado, ENT_QUOTES, 'UTF-8'); ?></li>
    <li><strong>Rol / Permisos Asignados:</strong> <?php echo ucfirst(htmlspecialchars($rolEmpleado, ENT_QUOTES, 'UTF-8')); ?></li>
    <li><strong>Hora de Actividad Local:</strong> <?php echo $horaAcceso; ?> hs.</li>
</ul>

<hr>

<h3>Métricas Actuales del Negocio (Información de Módulos)</h3>
<p><em>Resumen en tiempo real extraído de la base de datos:</em></p>

<ul>
    <li>
        <strong>Módulo Comercial:</strong> 
        Clientes Registrados: <?php echo $totalClientes; ?> agricultores.
    </li>
    <li>
        <strong>Módulo de Inventario:</strong> 
        Variedad de Insumos: <?php echo $totalProductos; ?> productos catalogados. | 
        Alertas de Stock Crítico: <?php echo $totalCritico; ?> insumos con 5 unidades o menos en almacén.
    </li>
    <li>
        <strong>Módulo Transaccional:</strong> 
        Ventas / Pedidos Totales: <?php echo $totalPedidos; ?> órdenes procesadas.
    </li>
</ul>

<hr>
<p><strong>Nota de Seguridad:</strong> Las opciones del menú superior están restringidas estrictamente de acuerdo a tu rol de usuario. Evita dejar tu sesión abierta al abandonar tu puesto físico.</p>

<?php 
// 6. UNIMOS EL CIERRE DEL LAYOUT
require_once __DIR__ . '/layout/footer.php'; 
?>