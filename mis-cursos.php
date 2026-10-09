<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/cursos-mock.php';
requerirSesion();

$usuario_id = $_SESSION['user_id']; 
$cursos_del_usuario = obtenerCursosDeUsuario($usuario_id);

require_once __DIR__ . '/../pages-front/mis-cursos.php'; // Ahora llama al .php
?>