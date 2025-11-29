<?php
// public/login.php

require_once __DIR__ . '/../app/pdo.php';
require_once __DIR__ . '/../app/utils.php';
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/csrf.php';

// 1. Inicializamos variables para evitar errores "Undefined variable"
$error = null;
$username = '';

// 2. Si ya estoy logueado, me manda directo al panel
if (isset($_SESSION['usuario_id'])) {
    redirect('/public/index.php');
}

// 3. PROCESAR EL FORMULARIO (Cuando le das al botón "Entrar")
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Verificamos seguridad CSRF (Token)
    verificar_csrf();

    // Recogemos datos (usando operador fusión null ?? para seguridad)
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validamos que no estén vacíos
    if (empty($username) || empty($password)) {
        $error = "Por favor, rellena todos los campos.";
    } else {
        // Buscamos el usuario en la BD
        $sql = "SELECT * FROM usuarios WHERE username = :u LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':u' => $username]);
        $usuario = $stmt->fetch();

        // Verificamos la contraseña
        if ($usuario && password_verify($password, $usuario['password'])) {
            // ¡LOGIN CORRECTO!
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['username']   = $usuario['username'];
            
            // Requisito PDF: Preferencia por cookie (ej: tema claro)
            setcookie('tema', 'claro', time() + (86400 * 30), "/");

            // Redirigimos al panel principal
            redirect('/public/index.php');
        } else {
            $error = "Usuario o contraseña incorrectos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Inventario</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; padding-top: 50px; background-color: #f4f4f4; }
        form { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); width: 300px; }
        input { width: 100%; padding: 8px; margin: 10px 0; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; cursor: pointer; border-radius: 4px; }
        button:hover { background: #0056b3; }
        .error { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 4px; margin-bottom: 10px; font-size: 0.9em; }
        h2 { text-align: center; color: #333; }
    </style>
</head>
<body>

    <form method="POST" action="">
        <h2>Acceso al Inventario</h2>

        <?php if ($error): ?>
            <div class="error"><?= e($error) ?></div>
        <?php endif; ?>

        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

        <label>Usuario:</label>
        <input type="text" name="username" value="<?= e($username) ?>" required autofocus>

        <label>Contraseña:</label>
        <input type="password" name="password" required>

        <button type="submit">Iniciar Sesión</button>
    </form>

</body>
</html>