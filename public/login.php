<?php
// ... (Tus requires y lógica PHP de login se mantienen igual) ...
// ...
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Inventario</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    
    <div class="login-wrapper">
        <div class="card login-card">
            <h2 style="text-align:center; margin-bottom: 1.5rem;">Acceso al Inventario</h2>
            
            <?php if ($error): ?>
                <div class="error-msg"><?php echo e($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                
                <div class="form-group">
                    <label>Usuario:</label>
                    <input type="text" name="username" required autofocus placeholder="Ej. admin">
                </div>

                <div class="form-group">
                    <label>Contraseña:</label>
                    <input type="password" name="password" required placeholder="******">
                </div>

                <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
            </form>
        </div>
    </div>

</body>
</html>