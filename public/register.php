<?php
// public/register.php

require_once __DIR__ . '/../app/pdo.php';
require_once __DIR__ . '/../app/utils.php';
require_once __DIR__ . '/../app/csrf.php';

// Solo iniciamos sesión si no está iniciada ya
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si ya está logueado, lo mandamos al panel
if (isset($_SESSION['usuario_id'])) {
    redirect('index.php');
}

$error = null;
$mensaje_exito = null;
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificar_csrf();

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $tema = $_POST['tema'] ?? 'claro'; // Recogemos el tema elegido

    // Validaciones básicas
    if (empty($username) || empty($password)) {
        $error = "Todos los campos son obligatorios.";
    } elseif ($password !== $confirm_password) {
        $error = "Las contraseñas no coinciden.";
    } else {
        // 1. Comprobar si el usuario ya existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = :u");
        $stmt->execute([':u' => $username]);
        
        if ($stmt->fetch()) {
            $error = "Ese nombre de usuario ya está cogido.";
        } else {
            // 2. Crear el usuario
            $hash = password_hash($password, PASSWORD_DEFAULT);

            try {
                $sql = "INSERT INTO usuarios (username, password) VALUES (:u, :p)";
                $stmt_insert = $pdo->prepare($sql);
                $stmt_insert->execute([
                    ':u' => $username,
                    ':p' => $hash
                ]);

                // Guardamos la preferencia de tema en la Cookie
                // Así cuando inicie sesión, ya se verá como él quiere
                setcookie('tema_preferido', $tema, time() + (86400 * 30), "/");

                // Aqui dejamos el mensaje de éxito
                $mensaje_exito = "¡Cuenta creada con éxito! Ya puedes iniciar sesión.";
                $username = ''; 

            } catch (PDOException $e) {
                $error = "Error en la base de datos: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Cuenta</title>
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
        .login-wrapper { min-height: 100vh; display: flex; align-items: center; justify-content: center; background-color: var(--fondo-cuerpo); }
        .login-card { width: 100%; max-width: 400px; text-align: center; background: var(--fondo-tarjeta); padding: 2rem; border-radius: 8px; border: 1px solid var(--borde); box-shadow: 0 4px 10px var(--sombra); }
        .form-group { margin-bottom: 15px; text-align: left; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #c3e6cb; }
        label { color: var(--texto-principal); }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">
        <h2>Crear Cuenta</h2>
        
        <?php if ($error): ?>
            <div class="alert-error"><?= e($error) ?></div>
        <?php endif; ?>

        <?php if ($mensaje_exito): ?>
            <div class="alert-success">
                <?= e($mensaje_exito) ?>
                <br><br>
                <a href="login.php" class="btn btn-success" style="width:100%; display:block;">Ir a Iniciar Sesión</a>
            </div>
        <?php else: ?>

            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                <div class="form-group">
                    <label>Nombre de Usuario</label>
                    <input type="text" name="username" value="<?= e($username) ?>" required autofocus>
                </div>

                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" required>
                </div>

                <div class="form-group">
                    <label>Confirmar Contraseña</label>
                    <input type="password" name="confirm_password" required>
                </div>

                <div class="form-group">
                    <label>Preferencia de Tema</label>
                    <select name="tema">
                        <option value="claro">Modo Dia</option>
                        <option value="oscuro">Modo Noche</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Registrarse</button>
            </form>
            
            <div style="margin-top: 20px;">
                <p>¿Ya tienes cuenta?</p>
                <a href="login.php" style="color: var(--azul-marca); font-weight: bold;">Iniciar Sesión</a>
            </div>

        <?php endif; ?>
    </div>
</div>

</body>
</html>