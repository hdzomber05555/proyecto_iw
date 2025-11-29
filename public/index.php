<?php
// Archivo principal de la aplicación que carga las dependencias necesarias
require_once __DIR__ . '/../app/pdo.php';
require_once __DIR__ . '/../app/utils.php';
require_once __DIR__ . '/../app/auth.php';

// Nos aseguramos de que el usuario haya iniciado sesión
obligar_login()

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Inventario</title>
    <style>
        body { font-family: sans-serif; padding: 20px; max-width: 1000px; margin: auto; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn { padding: 8px 15px; text-decoration: none; border-radius: 4px; color: white; display: inline-block; }
        .btn-green { background-color: #28a745; }
        .btn-logout { background-color: #dc3545; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f8f9fa; }
        
        /* Botones pequeños de la tabla */
        .action-link { margin-right: 10px; text-decoration: none; font-weight: bold; }
        .edit { color: #ffc107; }
        .delete { color: #dc3545; }
    </style>
</head>
<body>

    <div class="top-bar">
        <div>
            <h1>📦 Inventario</h1>
            <p>Usuario: <b><?= e($_SESSION['username']) ?></b></p>
        </div>
        <a href="logout.php" class="btn btn-logout">Cerrar Sesión</a>
    </div>

    <div style="margin-bottom: 15px;">
        <a href="items_form.php" class="btn btn-green">+ Nuevo Producto</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Ubicación</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= e($item['id']) ?></td>
                <td><?= e($item['nombre']) ?></td>
                <td><?= e($item['categoria']) ?></td>
                <td><?= e($item['ubicacion']) ?></td>
                <td><?= e($item['stock']) ?></td>
                <td>
                    <a href="items_form.php?id=<?= $item['id'] ?>" class="action-link edit">Editar</a>
                    <a href="items_delete.php?id=<?= $item['id'] ?>" class="action-link delete">Borrar</a>
                </td>
            </tr>
            <?php endforeach; ?>

            <?php if (empty($items)): ?>
            <tr>
                <td colspan="6" style="text-align: center;">No hay productos registrados.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    </body>
</html>
