<?php
require_once __DIR__ . '/../config/session.php';
requerirSesion();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FALK | Home</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <main class="acceso">
        <h1>Hola, <?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?></h1>
        <p>Iniciaste sesión correctamente.</p>
        <a href="../controllers/logout-controller.php" class="boton-secundario">Cerrar sesión</a>
    </main>
</body>
</html>