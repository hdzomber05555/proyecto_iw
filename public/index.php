<?php
require_once __DIR__ . '/../app/pdo.php';
require_once __DIR__ . '/../app/utils.php';
require_once __DIR__ . '/../app/auth.php';

// Seguridad si no inisiastes sesion
obligar_login();

//  RECUPERAR DATOS DE LA BD 
try {
    $stmt = $pdo->query("SELECT * FROM items");
    $items = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error al cargar datos: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php
    $tema = $_COOKIE['tema_preferido'] ?? 'claro';
    if ($tema === 'oscuro'):
    ?>
    <style>
        body { background-color: #222; color: #fff; }
        .card { background-color: #333; color: #fff; border: 1px solid #444; }
        table { color: #fff; }
        th { background-color: #444; color: #fff; }
        td { border-color: #555; }
        /* Ajustamos los inputs y selects por si acaso */
        input, select { background: #555; color: white; border: 1px solid #666; }
        h1, p { color: #fff; }
    </style>
    <?php endif; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario - Panel Principal</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <nav class="navbar">
        <a href="index.php" class="navbar-brand"> Gestión Inventario</a>
        <div class="nav-links">
            <a href="index.php">Inicio</a>
            <a href="#">Productos</a>
            <a href="preferencias.php">Preferencias</a>
            <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        
        <div class="card">
            <div class="header-flex">
                <div>
                    <h1>Hola, <?= e($_SESSION['username']) ?></h1>
                    <p>Resumen del estado actual del inventario.</p>
                </div>
                <a href="items_form.php" class="btn btn-success">+ Nuevo Producto</a>
            </div>

            <div class="table-responsive">
                <table class="inventory-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Ubicación</th> <th>Stock</th>
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
                            <td style="font-weight: bold;"><?= e($item['stock']) ?></td>
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

                        <?php if (empty($items)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 20px;">
                                No hay productos registrados aún.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            </div>
    </div>

</body>
</html>