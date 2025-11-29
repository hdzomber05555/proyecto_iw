<?php
// public/preferencias.php
require_once __DIR__ . '/../app/utils.php';
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/csrf.php';

obligar_login();

// Si enviaron el formulario, guardamos la cookie
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificar_csrf();

    $tema = $_POST['tema'];
    
    // Guardamos la cookie por 30 días
    setcookie('tema_preferido', $tema, time() + (86400 * 30), "/");

    // Redirigimos al inicio para ver el cambio
    redirect('index.php');
}

// Leemos el tema actual para marcarlo en el formulario
$tema_actual = $_COOKIE['tema_preferido'] ?? 'claro';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Preferencias</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body { font-family: sans-serif; padding: 50px; text-align: center; }
        .card { max-width: 400px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        select { padding: 10px; font-size: 16px; width: 100%; margin: 15px 0; }
        .btn { padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; border-radius: 4px; }
        .btn:hover { background: #0056b3; }
        a { display: block; margin-top: 15px; color: #666; }
    </style>
</head>
<body>

    <div class="card">
        <h1>⚙️ Preferencias</h1>
        <p>Elige tu tema visual favorito:</p>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

            <label>Tema de la interfaz:</label>
            <select name="tema">
                <option value="claro" <?= $tema_actual === 'claro' ? 'selected' : '' ?>>☀️ Modo Claro</option>
                <option value="oscuro" <?= $tema_actual === 'oscuro' ? 'selected' : '' ?>>🌙 Modo Oscuro</option>
            </select>

            <button type="submit" class="btn">Guardar Preferencias</button>
        </form>

        <a href="index.php">Cancelar y volver</a>
    </div>

</body>
</html>