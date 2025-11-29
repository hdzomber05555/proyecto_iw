<?php
// public/index.php
require_once __DIR__ . '/../app/pdo.php';
require_once __DIR__ . '/../app/utils.php';
require_once __DIR__ . '/../app/auth.php';

obligar_login();

// Consulta simple a la base de datos
$stmt = $pdo->query("SELECT * FROM items");
$items = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario</title>
    <link rel="stylesheet" href="css/styles.css">

    <?php if (isset($_COOKIE['tema_preferido']) && $_COOKIE['tema_preferido'] === 'oscuro'): ?>
    <style>
        :root {
            --fondo-cuerpo: #121212;      /* Negro fondo */
            --fondo-tarjeta: #1e1e1e;     /* Gris oscuro tarjetas */
            --fondo-input: #2d2d2d;       /* Gris para inputs */
            --texto-principal: #e0e0e0;   /* Letra blanca */
            --borde: #333333;             /* Bordes oscuros */
            --azul-marca: #4da3ff;        /* Azul más clarito */
        }
    </style>
    <?php endif; ?>
</head>
<body>

    <nav class="navbar">
        <a href="index.php" class="navbar-brand"> Gestión Inventario</a>
        <div class="nav-links">
            <a href="preferencias.php"> Preferencias</a> <a href="logout.php" style="color: #dc3545;">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h1>Hola, <?= e($_SESSION['username']) ?></h1>
                <a href="items_form.php" class="btn btn-success">+ Nuevo Producto</a>
            </div>
            
            <p>Estado actual del almacén:</p>

            <table class="inventory-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Ubicación</th>
                        <th>Stock</th>
                        <th>Estado</th>
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
                            <?php if ($item['stock'] < 5): ?>
                                <span class="badge badge-low">Bajo Stock</span>
                            <?php else: ?>
                                <span class="badge badge-ok">En Stock</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="items_form.php?id=<?= $item['id'] ?>" class="btn btn-warning">Editar</a>
                            <a href="items_delete.php?id=<?= $item['id'] ?>" class="btn btn-danger">Borrar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>