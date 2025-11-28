<?php

// Limpia texto antes de mostrarlo en pantalla para evitar XSS
function e($texto) {
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

// Redirigir al usuario a una URL específica
function redirect($url) {
    header("Location: $url");
    exit;
}
