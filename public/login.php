<?php
// public/login.php

require_once __DIR__ . '/../app/pdo.php';
require_once __DIR__ . '/../app/utils.php';
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/csrf.php';

// Inicializamos variables
$error = null;
$username = '';

// Si ya estoy logueado, al panel directo
if (isset($_SESSION['usuario_id'])) {
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    verificar_csrf();

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    // Recogemos el tema que haya elegido el usuario en el login
    $tema = $_POST['tema'] ?? 'claro';

    if (empty($username) || empty($password)) {
        $error = "Por favor, rellena todos los campos.";
    } else {
        $sql = "SELECT * FROM usuarios WHERE username = :u LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':u' => $username]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario['password'])) {
            // Login correcto
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['username']   = $usuario['username'];
            
            // Guardamos la cookie aqui mismo
            // Así cuando inicie sesión, ya se verá como él quiere
            setcookie('tema_preferido', $tema, time() + (86400 * 30), "/");

            redirect('index.php');
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
        /*Estilos específicos del Login */
        body { 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            background-color: var(--fondo-cuerpo); 
        }
        
        .login-card { 
            background: var(--fondo-tarjeta); 
            padding: 30px; 
            border-radius: 8px; 
            box-shadow: 0 4px 15px var(--sombra); 
            width: 100%; 
            max-width: 400px; 
            border: 1px solid var(--borde);
            text-align: center;
        }

        .form-group { margin-bottom: 15px; text-align: left; }
        
        label { display: block; margin-bottom: 5px; font-weight: bold; color: var(--texto-principal); }
        
        /* Botón de Entrar */
        button { 
            width: 100%; 
            padding: 10px; 
            background: var(--azul-marca); 
            color: white; 
            border: none; 
            cursor: pointer; 
            border-radius: 4px; 
            font-size: 16px; 
            margin-top: 10px;
        }
        button:hover { opacity: 0.9; }

        .error { 
            background-color: #f8d7da; 
            color: #721c24; 
            padding: 10px; 
            border-radius: 4px; 
            margin-bottom: 15px; 
            border: 1px solid #f5c6cb; 
        }

        /* Enlace de registro */
        .register-link {
            margin-top: 20px;
            font-size: 0.9rem;
            color: var(--texto-principal);
        }
        .register-link a {
            color: var(--azul-marca);
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h2 style="margin-bottom: 20px;">Acceso al Inventario</h2>

        <?php if ($error): ?>
            <div class="error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

            <div class="form-group">
                <label>Usuario:</label>
                <input type="text" name="username" value="<?= e($username) ?>" required autofocus>
            </div>

            <div class="form-group">
                <label>Contraseña:</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Preferencia de Tema:</label>
                <select name="tema">
                    <?php $tema_actual = $_COOKIE['tema_preferido'] ?? 'claro'; ?>
                    <option value="claro" <?= $tema_actual === 'claro' ? 'selected' : '' ?>>Modo Dia</option>
                    <option value="oscuro" <?= $tema_actual === 'oscuro' ? 'selected' : '' ?>>Modo Noche</option>
                </select>
            </div>

            <button type="submit">Iniciar Sesión</button>
        </form>

        <div class="register-link">
            ¿No tienes cuenta? <a href="register.php">Crea una aquí</a>
        </div>
    </div>

</body>
</html>