<?php


require_once __DIR__ . '/../app/utils.php';

// 1. Iniciamos la sesión para tener acceso a ella y poder destruirla
session_start();

// 2. Borramos todas las variables de sesión (usuario_id, username, etc.)
$_SESSION = [];

// 3. Borramos la cookie de sesión del navegador (Esto limpia el rastro en el cliente)
// Esto es una buena práctica de seguridad.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Destruimos la sesión en el servidor
session_destroy();

// 5. Redirigimos al usuario a la pantalla de Login
redirect('login.php');