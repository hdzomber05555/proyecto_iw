<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Generar un token CSRF si no existe
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
       // cree un token aleatorio y lo almacene en la sesión
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verificar el token CSRF enviado en formularios
function verificar_csrf() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die("Solicitud inválida: token CSRF invalido.");
        }
    }
}