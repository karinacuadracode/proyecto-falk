<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/cursos-mock.php';
requerirSesion();

// RF-18: el curso llega por la URL (certificado.php?curso=2)
$idCurso = filter_input(INPUT_GET, 'curso', FILTER_VALIDATE_INT);
$curso = $idCurso ? buscarCurso($idCurso) : null;

// RN-03: el certificado solo está disponible si el curso se completó en su totalidad
$disponible = $curso !== null && $curso['estado'] === 'finalizado';
if ($curso === null) {
    http_response_code(404);
} elseif (!$disponible) {
    http_response_code(403);
}

function e(string $t): string { return htmlspecialchars($t, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>FALK | Certificado</title>
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
            <li><a href="../controllers/logout-controller.php">Cerrar sesión</a></li>
        </ul>
    </nav>

    <main class="contenedor-principal">
        <section class="certificado-seccion" aria-labelledby="titulo-certificado">
            <?php if ($disponible): ?>
                <article class="certificado">
                    <img src="../pictures/logo-falk.png" alt="FALK - Plataforma de aprendizaje online" class="certificado-logo">
                    <h1 id="titulo-certificado" class="certificado-titulo">Certificado de finalización</h1>
                    <p>Se certifica que</p>
                    <p class="certificado-nombre"><?= e($_SESSION['username']) ?></p>
                    <p>completó en su totalidad el curso</p>
                    <p class="certificado-curso"><?= e($curso['nombre']) ?></p>
                    <p class="certificado-fecha">Finalizado el <?= e(date('d/m/Y', strtotime($curso['fecha_fin']))) ?></p>
                </article>

                <div class="certificado-acciones">
                    <button type="button" class="boton-imprimir" id="boton-imprimir">Imprimir / Guardar PDF</button>
                    <a href="mis-cursos.php" class="boton-volver">Volver a Mis cursos</a>
                </div>
            <?php else: ?>
                <!-- RN-03: curso en progreso o inexistente -->
                <div class="certificado-aviso" role="alert">
                    <h1 id="titulo-certificado">Certificado no disponible</h1>
                    <p>El certificado estará disponible cuando completes el curso en su totalidad.</p>
                </div>
                <a href="mis-cursos.php" class="boton-volver">Volver a Mis cursos</a>
            <?php endif; ?>
        </section>
    </main>

    <script src="../js/menu.js"></script>
    <script src="../js/certificado.js"></script>
</body>
</html>