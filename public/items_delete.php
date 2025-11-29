<?php
// public/items_delete.php

require_once __DIR__ . '/../app/pdo.php';
require_once __DIR__ . '/../app/utils.php';
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/csrf.php';

obligar_login();

$id = $_GET['id'] ?? null;
if (!$id) redirect('index.php');

$stmt = $pdo->prepare("SELECT * FROM items WHERE id = :id");
$stmt->execute([':id' => $id]);
$item = $stmt->fetch();

if (!$item) die("El producto no existe.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificar_csrf();

    try {
        $pdo->beginTransaction();

        $datos_item = json_encode($item, JSON_UNESCAPED_UNICODE);
        
        $sql_audit = "INSERT INTO auditoria_borrados (item_datos, usuario_id) VALUES (:datos, :u)";
        $stmt_audit = $pdo->prepare($sql_audit);
        $stmt_audit->execute([':datos' => $datos_item, ':u' => $_SESSION['usuario_id']]);

        $stmt_delete = $pdo->prepare("DELETE FROM items WHERE id = :id");
        $stmt_delete->execute([':id' => $id]);

        $pdo->commit();
        redirect('index.php');

    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        die("Error crítico al borrar: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Borrar Producto</title>
    
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
        /* Usamos variables para respetar el tema */
        body { 
            font-family: sans-serif; 
            display: flex; 
            justify-content: center; 
            padding-top: 50px; 
            background-color: var(--fondo-cuerpo); /* Antes era rojo fijo */
            color: var(--texto-principal);
        }
        
        .confirm-box { 
            background: var(--fondo-tarjeta); /* Antes era white */
            padding: 30px; 
            border-radius: 8px; 
            box-shadow: 0 0 15px var(--sombra); 
            text-align: center; 
            max-width: 400px;
            border: 1px solid var(--borde);
        }
        
        h2 { color: var(--peligro-rojo); }
        
        .btn { padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; cursor: pointer; border: none; font-size: 16px; margin: 10px; display: inline-block; }
        
        .btn-cancel { background-color: #6c757d; color: white; }
        .btn-delete { background-color: var(--peligro-rojo); color: white; }
        
        .item-info { 
            background: var(--fondo-cuerpo); /* Para que contraste con la tarjeta */
            padding: 10px; 
            border-radius: 4px; 
            margin: 20px 0; 
            text-align: left;
            border: 1px solid var(--borde);
        }
    </style>
</head>
<body>

    <div class="confirm-box">
        <h2>⚠️ ¿Estás seguro?</h2>
        <p>Vas a eliminar el siguiente producto de forma permanente:</p>
        
        <div class="item-info">
            <strong>Nombre:</strong> <?= e($item['nombre']) ?><br>
            <strong>Categoría:</strong> <?= e($item['categoria']) ?><br>
            <strong>Stock:</strong> <?= e($item['stock']) ?>
        </div>

        <p>Esta acción quedará registrada en la auditoría.</p>

        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            
            <a href="index.php" class="btn btn-cancel">Cancelar</a>
            <button type="submit" class="btn btn-delete">Sí, Borrar</button>
        </form>
    </div>

</body>
</html>