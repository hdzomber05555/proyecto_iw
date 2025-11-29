<?php
require_once __DIR__ . '/../app/pdo.php';
require_once __DIR__ . '/../app/utils.php';
require_once __DIR__ . '/../app/auth.php';

obligar_login();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Planificacion De Inventario</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                padding: 2rem;
            }
        </style>
    </head>
    <body>
        <h1>Bienvenido, <?= e($_SESSION['username']) ?> a la Planificacion De Inventario</h1>
        <p>Has iniciado sesión correctamente.</p>
    </body>
</html>
