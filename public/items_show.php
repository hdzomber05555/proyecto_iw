<?php
// public/items_show.php

require_once __DIR__ . '/../app/pdo.php';
require_once __DIR__ . '/../app/utils.php';
require_once __DIR__ . '/../app/auth.php';

obligar_login();

// Validar ID
$id = $_GET['id'] ?? null;
if (!$id) redirect('index.php');

// Buscar datos
$stmt = $pdo->prepare("SELECT * FROM items WHERE id = :id");
$stmt->execute([':id' => $id]);
$item = $stmt->fetch();

if (!$item) die("El producto no existe.");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Producto</title>
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
        .detail-card {
            max-width: 600px;
            margin: 50px auto;
            background: var(--fondo-tarjeta);
            padding: 30px;
            border-radius: 8px;
            border: 1px solid var(--borde);
            box-shadow: 0 4px 10px var(--sombra);
        }
        .detail-row {
            display: flex;
            border-bottom: 1px solid var(--borde);
            padding: 15px 0;
        }
        .detail-label {
            font-weight: bold;
            width: 150px;
            color: var(--texto-principal);
        }
        .detail-value {
            flex-grow: 1;
            color: var(--texto-principal);
        }
        .btn-back {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: var(--azul-marca);
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="detail-card">
        <h2 style="margin-top: 0;">📦 Ficha de Producto</h2>
        
        <div class="detail-row">
            <span class="detail-label">ID:</span>
            <span class="detail-value"><?= e($item['id']) ?></span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Nombre:</span>
            <span class="detail-value"><?= e($item['nombre']) ?></span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Categoría:</span>
            <span class="detail-value"><?= e($item['categoria']) ?></span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Ubicación:</span>
            <span class="detail-value"><?= e($item['ubicacion']) ?></span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Stock Actual:</span>
            <span class="detail-value" style="font-weight: bold;">
                <?= e($item['stock']) ?> unidades
            </span>
        </div>

        <div style="margin-top: 20px;">
            <a href="items_form.php?id=<?= $item['id'] ?>" class="btn btn-warning">Editar</a>
            <a href="index.php" class="btn-back" style="float: right;">Volver al listado</a>
        </div>
    </div>

</body>
</html>