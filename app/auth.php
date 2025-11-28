<?php
// Iniciar la sesión del usuario
session_start();

// Verificar si el usuario ha iniciado sesión si no, redirigir al login
function obligar_login() {
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: login.php');
        exit;
    }
}

