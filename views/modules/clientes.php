<?php
session_start();

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

    <p>
        <a href="../dashboard.php">◄ Volver al Dashboard</a> | 
        <a href="nuevo_cliente.php">Agregar nuevo cliente</a>
    </p>

    <h2>Gestión de Clientes (CRUD Comercial)</h2>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre / Razón Social</th>
                <th>CI / NIT</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Zona</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Recorrido lineal de registros de la BD
            while ($row = mysqli_fetch_array($resultado)) { 
            ?>
                <tr>
                    <td><?php echo $row['id_cliente']; ?></td>
                    <td><?php echo $row['nombre_completo']; ?></td>
                    <td><?php echo $row['ci_nit']; ?></td>
                    <td><?php echo !empty($row['telefono']) ? $row['telefono'] : 'N/A'; ?></td>
                    <td><?php echo $row['direccion']; ?></td>
                    <td><?php echo $row['zona']; ?></td>
                    <td>
                        <a href="editar_cliente.php?id=<?php echo $row['id_cliente']; ?>">Editar</a> | 
                        <a href="../../controllers/ClienteController.php?action=eliminar&id=<?php echo $row['id_cliente']; ?>" 
                           onclick="return confirm('¿Seguro que quieres eliminar este cliente del sistema?');">
                            Eliminar
                        </a>
                    </td>
                </tr>
            <?php 
            } 
            ?>
        </tbody>
    </table>

</body>
</html>