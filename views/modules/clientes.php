<?php
session_start();

// CÓDIGO GUARDIÁN ORIGINAL DE SEGURIDAD INTEGRAL - INTACTO
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] !== 'ventas' && $_SESSION['usuario_rol'] !== 'gerente')) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../../models/Cliente.php';

$modeloCliente = new Cliente();
$resultado = $modeloCliente->listarTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgroNova - Gestión de Clientes</title>
</head>
<body>

    <!-- INCLUSIÓN DE LA ESTRUCTURA PERSISTENTE DEL DASHBOARD -->
    <?php 
    require_once __DIR__ . '/../layout/header.php'; 
    require_once __DIR__ . '/../layout/nav.php'; 
    ?>

    <!-- Encabezado del Módulo con Botón de Acción Rápida -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-2 border-b border-slate-100">
        <div class="flex flex-col gap-1">
            <h2 class="text-2xl font-bold tracking-tight text-slate-800">Gestión de Clientes</h2>
            <p class="text-sm text-slate-400 font-medium">Administra la cartera de productores comerciales y datos de facturación.</p>
        </div>
        
        <!-- Botón Agregar Nuevo Cliente Estilo Donezo Mockup -->
        <a href="nuevo_cliente.php" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-agro-600 hover:bg-agro-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all shrink-0">
            <i data-lucide="user-plus" class="w-4 h-4"></i> Agregar nuevo cliente
        </a>
    </div>

    <!-- Contenedor de la Tabla Redondeada con Scroll Horizontal Seguro -->
    <div class="w-full overflow-x-auto border border-slate-100 rounded-2xl shadow-sm bg-white mt-4">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="py-4 px-6">ID</th>
                    <th class="py-4 px-6">Nombre / Razón Social</th>
                    <th class="py-4 px-6">CI / NIT</th>
                    <th class="py-4 px-6">Teléfono</th>
                    <th class="py-4 px-6">Dirección</th>
                    <th class="py-4 px-6">Zona</th>
                    <th class="py-4 px-6 text-center">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-600 font-medium">
                <?php 
                // Recorrido lineal de registros de la BD
                while ($row = mysqli_fetch_array($resultado)) { 
                ?>
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-4 px-6 text-slate-400 font-mono text-xs">#<?php echo htmlspecialchars($row['id_cliente'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="py-4 px-6 text-slate-800 font-semibold"><?php echo htmlspecialchars($row['nombre_completo'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="py-4 px-6 font-mono text-xs"><?php echo htmlspecialchars($row['ci_nit'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="py-4 px-6">
                            <?php if (!empty($row['telefono']) && $row['telefono'] !== 'N/A') { ?>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs">
                                    <i data-lucide="phone" class="w-3 h-3 text-slate-400"></i> <?php echo htmlspecialchars($row['telefono'], ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            <?php } else { ?>
                                <span class="text-slate-300 italic text-xs">No asignado</span>
                            <?php } ?>
                        </td>
                        <td class="py-4 px-6 text-slate-500 truncate max-w-xs"><?php echo htmlspecialchars($row['direccion'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-agro-50 text-agro-700 text-xs font-semibold">
                                <?php echo htmlspecialchars($row['zona'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <!-- Botón de Editar Estilizado como enlace de software moderno -->
                            <a href="editar_cliente.php?id=<?php echo urlencode($row['id_cliente']); ?>" class="inline-flex items-center gap-1 text-xs font-bold text-agro-600 hover:text-agro-700 hover:underline transition-all">
                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Editar
                            </a>
                        </td>
                    </tr>
                <?php 
                } 
                ?>
            </tbody>
        </table>
    </div>

    <!-- INCLUSIÓN DEL CIERRE DEL CONTENEDOR Y HTML -->
    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>