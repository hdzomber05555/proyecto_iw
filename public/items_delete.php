<?php
// public/items_delete.php

require_once __DIR__ . '/../app/pdo.php';
require_once __DIR__ . '/../app/utils.php';
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/csrf.php';

obligar_login();

// 1. Validamos que llegue un ID
$id = $_GET['id'] ?? null;
if (!$id) {
    redirect('index.php');
}

// 2. Buscamos el ítem para confirmar que existe (y mostrar su nombre)
$stmt = $pdo->prepare("SELECT * FROM items WHERE id = :id");
$stmt->execute([':id' => $id]);
$item = $stmt->fetch();

if (!$item) {
    die("El producto no existe.");
}

// 3. PROCESAMOS EL BORRADO (Solo si es POST y con Token)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificar_csrf();

    try {
        // --- INICIO DE LA TRANSACCIÓN (Requisito PDF) ---
        $pdo->beginTransaction();

        // PASO A: Guardar en Auditoría (Requisito PDF)
        // Guardamos todos los datos del ítem en formato JSON para tener un respaldo
        $datos_item = json_encode($item, JSON_UNESCAPED_UNICODE);
        
        $sql_audit = "INSERT INTO auditoria_borrados (item_datos, usuario_id) VALUES (:datos, :u)";
        $stmt_audit = $pdo->prepare($sql_audit);
        $stmt_audit->execute([
            ':datos' => $datos_item,
            ':u'     => $_SESSION['usuario_id']
        ]);

        // PASO B: Borrar el ítem de verdad
        $stmt_delete = $pdo->prepare("DELETE FROM items WHERE id = :id");
        $stmt_delete->execute([':id' => $id]);

        // --- CONFIRMAR CAMBIOS ---
        $pdo->commit();
        
        // Todo salió bien, volvemos al listado
        redirect('index.php');

    } catch (Exception $e) {
        // --- ROLLBACK (Requisito PDF) ---
        // Si algo falló, deshacemos todo (ni se borra, ni se audita)
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        die("Error crítico al borrar: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Borrar Producto</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; padding-top: 50px; background-color: #f8d7da; }
        .confirm-box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.2); text-align: center; max-width: 400px; }
        h2 { color: #721c24; }
        .btn { padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; cursor: pointer; border: none; font-size: 16px; margin: 10px; }
        .btn-cancel { background-color: #6c757d; color: white; }
        .btn-delete { background-color: #dc3545; color: white; }
        .item-info { background: #eee; padding: 10px; border-radius: 4px; margin: 20px 0; text-align: left; }
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
