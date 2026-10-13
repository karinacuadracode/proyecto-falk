<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/cursos-mock.php';
requerirSesion();

// El curso llega por la URL (curso.php?curso=1) y solo se muestra si es del usuario logueado
$idCurso = filter_input(INPUT_GET, 'curso', FILTER_VALIDATE_INT);
$curso = $idCurso ? buscarCurso($idCurso, $_SESSION['user_id']) : null;
if ($curso === null) {
    http_response_code(404);
}

// Contenido de ejemplo de la primera lección (todavía no hay lecciones en la base)
$lecciones = [
    'Programación inicial' => ['Módulo 1: Conceptos básicos de algoritmos', 'En este video aprenderemos los fundamentos de la lógica de programación.'],
    'Base de Datos'        => ['Módulo 1: Introducción a SQL y modelado relacional', 'En este video aprenderemos a estructurar bases de datos y realizar las primeras consultas SQL.'],
    'Programación Web'     => ['Módulo 1: Estructuras HTML y maquetado CSS', 'En este video aprenderemos a maquetar una página web desde cero usando HTML5 y CSS3.'],
];
if ($curso !== null) {
    [$modulo, $descripcion] = $lecciones[$curso['nombre']]
        ?? ['Módulo 1: Introducción', 'En este video conocerás los temas principales del curso y cómo está organizado.'];
}

function e(string $t): string { return htmlspecialchars($t, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>FALK | <?= $curso ? e($curso['nombre']) : 'Curso no encontrado' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <header class="barra-superior">
        <button type="button" class="boton-icono" id="boton-menu" aria-label="Abrir menú" aria-expanded="false" aria-controls="menu-principal">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>

        <a href="home.php"><img src="../pictures/logo-falk.png" alt="FALK - Volver al inicio" class="logo"></a>

        <a href="perfil.php" class="boton-icono" aria-label="Mi perfil">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <circle cx="12" cy="7" r="4.5"/>
                <path d="M3 21c0-4.4 4-7 9-7s9 2.6 9 7z"/>
            </svg>
        </a>
    </header>

    <!-- Menú de navegación: arranca oculto (hidden) y lo abre js/menu.js -->
    <nav id="menu-principal" class="menu-lateral" aria-label="Menú principal" hidden>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="mis-cursos.php">Mis cursos</a></li>
            <li><a href="calendario.php">Calendario</a></li>
            <?php if (esAdmin()): ?>
                <li><a href="admin-logs.php">Listado de accesos</a></li>
                <li><a href="admin-usuarios.php">Gestión de usuarios</a></li>
            <?php endif; ?>
            <li><a href="../controllers/logout-controller.php">Cerrar sesión</a></li>
        </ul>
    </nav>

    <main class="contenedor-principal">
        <section class="curso-seccion" aria-labelledby="titulo-curso">
            <?php if ($curso !== null): ?>
                <h1 id="titulo-curso" class="curso-titulo"><?= e($curso['nombre']) ?></h1>
                <p class="curso-modulo"><?= e($modulo) ?></p>

                <!-- Reproductor de ejemplo: todavía no hay videos cargados -->
                <div class="curso-video" role="img" aria-label="Video de la clase (no disponible por ahora)">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <polygon points="6 4 20 12 6 20 6 4"/>
                    </svg>
                </div>

                <article class="curso-material">
                    <h2>Material de lectura</h2>
                    <p><?= e($descripcion) ?></p>
                </article>

                <?php if ($curso['estado'] === 'finalizado'): ?>
                    <a href="certificado.php?curso=<?= (int) $curso['id'] ?>" class="curso-certificado">Ver certificado</a>
                <?php endif; ?>
            <?php else: ?>
                <div class="certificado-aviso" role="alert">
                    <h1 id="titulo-curso">Curso no encontrado</h1>
                    <p>El curso no existe o no estás inscripto.</p>
                </div>
            <?php endif; ?>

            <a href="mis-cursos.php" class="boton-volver">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
                Volver a Mis cursos
            </a>
        </section>
    </main>

    <script src="../js/menu.js"></script>
</body>
</html>
