<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/session.php';
iniciarSesionSegura();
cerrarSesion();

header('Location: ../index.html');
exit;