<?php
require_once __DIR__ . '/../app/pdo.php';
require_once __DIR__ . '/../app/utils.php';
require_once __DIR__ . '/../app/auth.php';

obligar_login();

// Configuración de paginación y búsqueda
$registros_por_pagina = 5; // Mostrar 5 productos por página
$pagina_actual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($pagina_actual < 1) $pagina_actual = 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// capturamos el término de búsqueda si existe
$busqueda = isset($_GET['q']) ? trim($_GET['q']) : '';

try {
    // calculamos el total de registros que coinciden con la búsqueda
    // Usamos dos variables distintas (:q1 y :q2) aunque tengan el mismo valor
    $sql_count = "SELECT COUNT(*) FROM items WHERE nombre LIKE :q1 OR categoria LIKE :q2";
    $stmt_count = $pdo->prepare($sql_count);
    $stmt_count->execute([
        ':q1' => "%$busqueda%",
        ':q2' => "%$busqueda%"
    ]);
    $total_registros = $stmt_count->fetchColumn();
    
    $total_paginas = ceil($total_registros / $registros_por_pagina);

    // Consulta para obtener datos con búsqueda y paginación
    // Aquí también separamos :q1 y :q2 para evitar el error HY093
    $sql = "SELECT * FROM items 
            WHERE nombre LIKE :q1 OR categoria LIKE :q2 
            LIMIT :limit OFFSET :offset";
    
    $stmt = $pdo->prepare($sql);
    
    // Vinculamos cada parámetro por separado
    $stmt->bindValue(':q1', "%$busqueda%", PDO::PARAM_STR);
    $stmt->bindValue(':q2', "%$busqueda%", PDO::PARAM_STR);
    $stmt->bindValue(':limit', $registros_por_pagina, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    $items = $stmt->fetchAll();

} catch (PDOException $e) {
    die("Error en la base de datos: " . $e->getMessage());
}
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
            --fondo-cuerpo: #121212;
            --fondo-tarjeta: #1e1e1e;
            --fondo-input: #2d2d2d;
            --texto-principal: #e0e0e0;
            --borde: #333333;
            --azul-marca: #4da3ff;
        }
    </style>
    <?php endif; ?>

    <style>
        /* Estilos extra para el buscador y paginación */
        .search-box { margin-bottom: 20px; display: flex; gap: 10px; }
        .search-box input { flex-grow: 1; }
        .pagination { margin-top: 20px; text-align: center; }
        .pagination a { 
            padding: 8px 12px; border: 1px solid var(--borde); 
            text-decoration: none; color: var(--texto-principal); margin: 0 5px; border-radius: 4px; 
        }
        .pagination a.active { background-color: var(--azul-marca); color: white; border-color: var(--azul-marca); }
        .pagination a:hover:not(.active) { background-color: #ddd; color: black; }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="index.php" class="navbar-brand">Gestión Inventario</a>
        <div class="nav-links">
            <a href="preferencias.php">Preferencias</a>
            <a href="logout.php" style="color: #dc3545;">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1>Hola, <?= e($_SESSION['username']) ?></h1>
                <a href="items_form.php" class="btn btn-success">+ Nuevo Producto</a>
            </div>
            
            <form class="search-box" method="GET" action="index.php">
                <input type="text" name="q" placeholder="Buscar por nombre o categoría..." value="<?= e($busqueda) ?>">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <?php if ($busqueda): ?>
                    <a href="index.php" class="btn btn-warning">Limpiar</a>
                <?php endif; ?>
            </form>

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
                             <a href="items_show.php?id=<?= $item['id'] ?>" class="btn btn-primary" style="background-color: #17a2b8;">Ver</a>
                            <a href="items_form.php?id=<?= $item['id'] ?>" class="btn btn-warning">Editar</a>
                            <a href="items_delete.php?id=<?= $item['id'] ?>" class="btn btn-danger">Borrar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                    <?php if (empty($items)): ?>
                    <tr><td colspan="7" style="text-align:center;">No se encontraron resultados.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php if ($total_paginas > 1): ?>
            <div class="pagination">
                <?php if ($pagina_actual > 1): ?>
                    <a href="?page=<?= $pagina_actual - 1 ?>&q=<?= e($busqueda) ?>">« Anterior</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <a href="?page=<?= $i ?>&q=<?= e($busqueda) ?>" class="<?= $i === $pagina_actual ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($pagina_actual < $total_paginas): ?>
                    <a href="?page=<?= $pagina_actual + 1 ?>&q=<?= e($busqueda) ?>">Siguiente »</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        </div>
    </div>

</body>
</html>