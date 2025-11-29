<?php
// public/items_form.php

require_once __DIR__ . '/../app/pdo.php';
require_once __DIR__ . '/../app/utils.php';
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/csrf.php';

obligar_login();

// Variables iniciales
$id = $_GET['id'] ?? null;
$nombre = '';
$categoria = '';
$ubicacion = '';
$stock = 0;

$errores = [];
$titulo = 'Nuevo Producto';
$texto_boton = 'Guardar';

// Carga de datos si es edición
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM items WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $item = $stmt->fetch();

    if (!$item) {
        die("El producto no existe.");
    }

    $nombre = $item['nombre'];
    $categoria = $item['categoria'];
    $ubicacion = $item['ubicacion'];
    $stock = $item['stock'];
    
    $titulo = 'Editar Producto: ' . e($nombre);
    $texto_boton = 'Actualizar';
}

// Procesar Formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificar_csrf();

    $nombre = trim($_POST['nombre']);
    $categoria = trim($_POST['categoria']);
    $ubicacion = trim($_POST['ubicacion']);
    $stock = (int) $_POST['stock'];

    if (empty($nombre)) $errores[] = "El nombre del producto es obligatorio.";
    if ($stock < 0) $errores[] = "El stock no puede ser negativo.";

    if (empty($errores)) {
        try {
            if ($id) {
                $sql = "UPDATE items SET nombre=:n, categoria=:c, ubicacion=:u, stock=:s WHERE id=:id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':n'=>$nombre, ':c'=>$categoria, ':u'=>$ubicacion, ':s'=>$stock, ':id'=>$id]);
            } else {
                $sql = "INSERT INTO items (nombre, categoria, ubicacion, stock) VALUES (:n, :c, :u, :s)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':n'=>$nombre, ':c'=>$categoria, ':u'=>$ubicacion, ':s'=>$stock]);
            }
            redirect('index.php');
        } catch (PDOException $e) {
            $errores[] = "Error en base de datos: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?></title>
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
        /* Usamos var(--fondo-tarjeta) en vez de #fff para que se ponga negro en modo oscuro */
        .form-container { 
            max-width: 500px; 
            margin: 50px auto; 
            padding: 20px; 
            border: 1px solid var(--borde); 
            border-radius: 8px; 
            background: var(--fondo-tarjeta); 
        }
        
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: var(--texto-principal); }
        
        /* Los inputs ya cogen estilo del css/styles.css, pero aseguramos ancho */
        input[type="text"], input[type="number"] { width: 100%; box-sizing: border-box; }
        
        .btn-submit { 
            background-color: var(--ok-verde); 
            color: white; 
            padding: 10px 15px; 
            border: none; 
            cursor: pointer; 
            border-radius: 4px; 
            width: 100%; 
            font-size: 16px; 
        }
        
        .btn-back { 
            display: inline-block; 
            margin-bottom: 20px; 
            color: var(--texto-principal); /* Para que se vea en modo oscuro */
            text-decoration: none; 
        }
        
        .alert-danger { 
            background-color: #f8d7da; 
            color: #721c24; 
            padding: 10px; 
            border-radius: 4px; 
            margin-bottom: 20px; 
            border: 1px solid #f5c6cb; 
        }
    </style>
</head>
<body>

    <div class="form-container">
        <a href="index.php" class="btn-back">← Volver al listado</a>
        
        <h2><?= $titulo ?></h2>

        <?php if (!empty($errores)): ?>
            <div class="alert-danger">
                <ul>
                    <?php foreach ($errores as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

            <div class="form-group">
                <label>Nombre del Producto *</label>
                <input type="text" name="nombre" value="<?= e($nombre) ?>" required autofocus>
            </div>

            <div class="form-group">
                <label>Categoría</label>
                <input type="text" name="categoria" value="<?= e($categoria) ?>">
            </div>

            <div class="form-group">
                <label>Ubicación</label>
                <input type="text" name="ubicacion" value="<?= e($ubicacion) ?>">
            </div>

            <div class="form-group">
                <label>Stock (Cantidad)</label>
                <input type="number" name="stock" value="<?= e($stock) ?>" min="0">
            </div>

            <button type="submit" class="btn-submit"><?= $texto_boton ?></button>
        </form>
    </div>

</body>
</html>