<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    die("Acceso denegado de facturación.");
}
require_once __DIR__ . '/../../config/conexion.php';
$db = Conexion::conectar();
$id_pedido = intval($_GET['id'] ?? 0);
// Consulta en bruto para extraer toda la información cruzada de la venta
$sql = "SELECT p.id_pedido, p.cantidad, p.total_pagar, p.fecha_registro, p.estado_entrega,
               c.nombre_completo AS cliente, c.ci_nit, c.direccion, c.zona,
               pr.nombre_insumo, pr.precio_venta
        FROM pedidos p
        INNER JOIN clientes c ON p.id_cliente = c.id_cliente
        INNER JOIN productos pr ON p.id_producto = pr.id_producto
        WHERE p.id_pedido = $id_pedido LIMIT 1";

$resultado = mysqli_query($db, $sql);
$data = mysqli_fetch_array($resultado);
if (!$data) {
    die("Error: El registro de la factura no existe.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura de Venta #<?php echo $data['id_pedido']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #ffffff; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; margin: 0; }
        }
    </style>
</head>
<body class="p-8 text-slate-800 max-w-3xl mx-auto">
    <div class="no-print mb-6 flex justify-between items-center bg-slate-50 p-4 rounded-xl border border-slate-100">
        <span class="text-xs font-medium text-slate-500">Previsualización de Documento de Venta Oficial.</span>
        <button onclick="window.print();" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm">
            🖨️ Confirmar / Descargar PDF
        </button>
    </div>
    <div class="flex justify-between items-start border-b-2 border-slate-100 pb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-emerald-700">AgroNova Distribuciones S.R.L.</h1>
            <p class="text-xs text-slate-400 font-medium mt-1">Suministros Agrícolas de Alta Calidad</p>
            <p class="text-xs text-slate-500 mt-3">Cochabamba - Bolivia</p>
        </div>
        <div class="text-right bg-slate-50 border border-slate-200 rounded-2xl p-5 min-w-[200px]">
            <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Factura Comercial</h2>
            <p class="text-xl font-mono font-bold text-slate-800 mt-1">#OD-00<?php echo $data['id_pedido']; ?></p>
            <span class="block text-[10px] text-slate-400 font-mono mt-2">Fecha: <?php echo $data['fecha_registro']; ?></span>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-6 my-8 bg-slate-50/50 border border-slate-100 rounded-xl p-5 text-xs">
        <div>
            <h3 class="font-bold text-slate-400 uppercase tracking-wide mb-2">Señor(es):</h3>
            <p class="text-sm font-bold text-slate-800"><?php echo htmlspecialchars($data['cliente'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p class="text-slate-500 mt-1">Dirección: <?php echo htmlspecialchars($data['direccion'] . " (" . $data['zona'] . ")", ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
        <div class="text-right">
            <h3 class="font-bold text-slate-400 uppercase tracking-wide mb-2">Información Fiscal:</h3>
            <p class="text-sm font-mono font-bold text-slate-800">NIT/CI: <?php echo htmlspecialchars($data['ci_nit'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p class="text-slate-400 mt-1">Operador: <?php echo htmlspecialchars($_SESSION['usuario_nombre'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
    </div>
    <table class="w-full text-left text-xs border-collapse border border-slate-200 rounded-xl overflow-hidden shadow-sm">
        <thead>
            <tr class="bg-slate-100 font-bold text-slate-600 border-b border-slate-200 uppercase tracking-wider">
                <th class="p-4">Descripción del Insumo Agrícola</th>
                <th class="p-4 text-center">Cantidad</th>
                <th class="p-4 text-right">Precio Unitario</th>
                <th class="p-4 text-right">Total Parcial</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200 font-medium text-slate-700">
            <tr>
                <td class="p-4 font-bold text-slate-900"><?php echo htmlspecialchars($data['nombre_insumo'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td class="p-4 text-center font-mono"><?php echo htmlspecialchars($data['cantidad'], ENT_QUOTES, 'UTF-8'); ?> uds.</td>
                <td class="p-4 text-right font-mono">Bs. <?php echo number_format($data['precio_venta'], 2); ?></td>
                <td class="p-4 text-right font-mono font-bold text-slate-900">Bs. <?php echo number_format($data['total_pagar'], 2); ?></td>
            </tr>
            <tr class="bg-slate-50/80 font-bold">
                <td colspan="2"></td>
                <td class="p-4 text-right text-slate-500 text-sm uppercase">Total Neto Cobrado:</td>
                <td class="p-4 text-right text-emerald-700 text-base font-mono">Bs. <?php echo number_format($data['total_pagar'], 2); ?></td>
            </tr>
        </tbody>
    </table>
    <div class="mt-12 pt-6 border-t border-slate-100 text-[10px] text-slate-400 font-medium leading-relaxed">
        <p class="font-bold text-slate-600 mb-1">Términos Comerciales Generales:</p>
        Esta factura constituye un documento de respaldo oficial para el despacho físico de almacén. Los insumos agrícolas adquiridos se rigen bajo los controles de acopio autorizados de AgroNova Distribuciones. Estado logístico inicial de orden de despacho: <strong><?php echo htmlspecialchars($data['estado_entrega'], ENT_QUOTES, 'UTF-8'); ?></strong>.
    </div>

    <!-- ORDEN AUTOMÁTICA DE DISPARO DE IMPRESIÓN PDF -->
    <script>
        window.onload = function() {
            // Cuando la hoja termina de acomodar los datos, abre instantáneamente el guardado PDF del sistema operativo
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>

</body>
</html>