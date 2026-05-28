<?php
session_start();

// CÓDIGO GUARDIÁN DE SEGURIDAD - INTACTO
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] !== 'ventas' && $_SESSION['usuario_rol'] !== 'gerente')) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../config/conexion.php';
$db = Conexion::conectar();

$id = intval($_GET['id'] ?? 0);
$sql = "SELECT * FROM clientes WHERE id_cliente = $id LIMIT 1";
$resultado = mysqli_query($db, $sql);
$cliente = mysqli_fetch_array($resultado);

if (!$cliente) {
    die("Error: Cliente no encontrado en el sistema.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Editar Cliente</title>
</head>
<body>

    <!-- INCLUSIÓN DE LA ESTRUCTURA PERSISTENTE -->
    <?php 
    require_once __DIR__ . '/../layout/header.php'; 
    require_once __DIR__ . '/../layout/nav.php'; 
    ?>

    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-2 border-b border-slate-100">
        <div class="flex flex-col gap-1">
            <h2 class="text-2xl font-bold tracking-tight text-slate-800">Modificar Datos del Cliente</h2>
            <p class="text-sm text-slate-400 font-medium">Corrige o actualiza los datos del cliente seleccionado en la base de datos.</p>
        </div>
        <a href="clientes.php" class="inline-flex items-center justify-center gap-2 px-3.5 py-2 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold rounded-xl shadow-sm transition-all shrink-0">
            <i data-lucide="x" class="w-3.5 h-3.5"></i> Cancelar cambios
        </a>
    </div>

    <!-- Contenedor Formulario -->
    <div class="max-w-2xl bg-white border border-slate-100 rounded-2xl shadow-sm p-8 mt-4">
        <form action="../../controllers/ClienteController.php?action=editar" method="POST" class="space-y-5">
            <input type="hidden" name="id_cliente" value="<?php echo htmlspecialchars($cliente['id_cliente'], ENT_QUOTES, 'UTF-8'); ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Nombre Completo / Razón Social <span class="text-red-500">*</span></label>
                    <input type="text" name="nombre_completo" value="<?php echo htmlspecialchars($cliente['nombre_completo'], ENT_QUOTES, 'UTF-8'); ?>" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Documento de Identidad / NIT <span class="text-red-500">*</span></label>
                    <input type="text" name="ci_nit" value="<?php echo htmlspecialchars($cliente['ci_nit'], ENT_QUOTES, 'UTF-8'); ?>" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Teléfono / Celular</label>
                    <input type="text" name="telefono" value="<?php echo htmlspecialchars($cliente['telefono'], ENT_QUOTES, 'UTF-8'); ?>" 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Zona / Ciudad Base <span class="text-red-500">*</span></label>
                    <input type="text" name="zona" value="<?php echo htmlspecialchars($cliente['zona'], ENT_QUOTES, 'UTF-8'); ?>" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
                </div>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Dirección Exacta <span class="text-red-500">*</span></label>
                <input type="text" name="direccion" value="<?php echo htmlspecialchars($cliente['direccion'], ENT_QUOTES, 'UTF-8'); ?>" required 
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-700 focus:outline-none focus:border-agro-600 focus:ring-1 focus:ring-agro-600 transition-all">
            </div>

            <div class="pt-2 border-t border-slate-100 flex justify-end">
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-agro-600 hover:bg-agro-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i> Actualizar Ficha Comercial
                </button>
            </div>
        </form>
    </div>

    <!-- INCLUSIÓN DEL CIERRE -->
    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>