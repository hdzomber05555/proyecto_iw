<?php

require_once __DIR__ . '/../app/pdo.php';
require_once __DIR__ . '/../app/utils.php';
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/csrf.php';

// Si ya estoy logueado, me manda directo al panel
if (isset($_SESSION['usuario_id'])) {
    redirect('/public/index.php');
}

// ARREGLO 1: Inicializamos las variables AL PRINCIPIO del archivo.
// Así nunca estarán "undefined" (indefinidas).
$username = ''; 
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    verificar_csrf();

    // Recogemos datos (Usamos el operador null coalescing ?? por seguridad)
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
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['username']   = $usuario['username'];
            
            // Preferencia por cookie (Requisito del PDF [cite: 28])
            // Guardamos un tema por defecto si no tiene
            setcookie('tema', 'claro', time() + (86400 * 30), "/");

            redirect('/public/index.php');
        } else {
            $error = "Usuario o contraseña incorrectos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 2rem;
        }

        form {
            max-width: 300px;
            margin: auto;
        }

        input {
            width: 100%;
            padding: 0.5rem;
            margin: 0.5rem 0;
        }

        button {
            padding: 0.5rem;
            width: 100%;
            background-color: #007bff;
            color: white;
            border: none;
        }

        button:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>

        <form method="POST" action="">
            <h2>Iniciar Sesión</h2>
            <?php if ($error): ?>
                <div class="error"><?php echo e($error); ?></div>
                <?php endif; ?>
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <label>Usuario:</label>
                <input type="text" name="username" required autofocus>
                <label>Contraseña:</label>
                <input type="password" name="password" required>
                <button type="submit">Iniciar Sesión</button>
        </form>
</body>
</html>