<?php


require_once __DIR__ . '/../app/utils.php';

// Iniciamos la sesión para tener acceso a ella y poder destruirla
session_start();

// Borramos todas las variables de sesión
$_SESSION = [];

// Borramos la cookie de sesión del navegador 
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruimos la sesión en el servidor
session_destroy();

// Redirigimos al usuario a la pantalla de Login
redirect('login.php');