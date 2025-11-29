<?php
require_once __DIR__ . '/../app/pdo.php';
require_once __DIR__ . '/../app/utils.php';
require_once __DIR__ . '/../app/auth.php';

obligar_login();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario - Panel Principal</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <nav class="navbar">
        <a href="index.php" class="navbar-brand">📦 Gestión Inventario</a>
        <div class="nav-links">
            <a href="index.php">Inicio</a>
            <a href="#">Productos</a>
            <a href="#">Reportes</a>
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
                <a href="#" class="btn btn-success">+ Nuevo Producto</a>
            </div>

            <div class="table-responsive">
                <table class="inventory-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Stock</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>101</td>
                            <td>Laptop HP Pavilion</td>
                            <td>Electrónica</td>
                            <td>15</td>
                            <td>$450.00</td>
                            <td><span class="badge badge-ok">En Stock</span></td>
                            <td>
                                <a href="#" class="btn btn-warning">Editar</a>
                                <a href="#" class="btn btn-danger">Borrar</a>
                            </td>
                        </tr>
                        <tr>
                            <td>102</td>
                            <td>Mouse Inalámbrico</td>
                            <td>Accesorios</td>
                            <td>3</td>
                            <td>$12.50</td>
                            <td><span class="badge badge-low">Bajo Stock</span></td>
                            <td>
                                <a href="#" class="btn btn-warning">Editar</a>
                                <a href="#" class="btn btn-danger">Borrar</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            </div>
    </div>

</body>
</html>
