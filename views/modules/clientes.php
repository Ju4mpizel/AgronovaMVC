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

    <?php 
    require_once __DIR__ . '/../layout/header.php'; 
    require_once __DIR__ . '/../layout/nav.php'; 
    ?>

    <h2>Gestión de Clientes (CRUD Comercial)</h2>

    <p>
        <a href="nuevo_cliente.php">Agregar nuevo cliente</a>
    </p>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre / Razón Social</th>
                <th>CI / NIT</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Zona</th>
                <th>Acción</th> </tr>
        </thead>
        <tbody>
            <?php 
            // Recorrido lineal de registros de la BD
            while ($row = mysqli_fetch_array($resultado)) { 
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_cliente'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_completo'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['ci_nit'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars(!empty($row['telefono']) ? $row['telefono'] : 'N/A', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['direccion'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['zona'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <a href="editar_cliente.php?id=<?php echo urlencode($row['id_cliente']); ?>">Editar</a>
                    </td>
                </tr>
            <?php 
            } 
            ?>
        </tbody>
    </table>

    <?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>