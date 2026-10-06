<?php
require_once __DIR__ . '/../config/session.php';
requerirSesion();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Calendario académico con tus eventos en FALK, plataforma de aprendizaje online.">
    <meta name="robots" content="noindex, nofollow">

    <title>FALK | Calendario</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <!--CSS-->
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

        <a href="#" class="boton-icono" aria-label="Mi perfil">
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
            <li><a href="calendario.php" aria-current="page">Calendario</a></li>
            <li><a href="../controllers/logout-controller.php">Cerrar sesión</a></li>
        </ul>
    </nav>

    <main class="contenedor-principal">
        <section class="seccion-home">
            <h1>Calendario</h1>

            <div class="calendario-mes">
                <div class="calendario-navegacion">
                    <button type="button" class="boton-icono" id="mes-anterior" aria-label="Mes anterior">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                            <polyline points="15 6 9 12 15 18"/>
                        </svg>
                    </button>

                    <h2 id="titulo-mes" aria-live="polite"></h2>

                    <button type="button" class="boton-icono" id="mes-siguiente" aria-label="Mes siguiente">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                            <polyline points="9 6 15 12 9 18"/>
                        </svg>
                    </button>
                </div>

                <div class="calendario-dias-semana" aria-hidden="true">
                    <span>L</span><span>M</span><span>M</span><span>J</span><span>V</span><span>S</span><span>D</span>
                </div>

                <!-- Los días los dibuja js/calendario.js -->
                <div id="grilla-calendario" class="calendario-grilla"></div>
            </div>

            <div id="detalle-eventos" class="detalle-eventos" aria-live="polite">
                <p>Elegí un día para ver tus eventos.</p>
            </div>
        </section>
    </main>

    <!--JavaScript-->
    <script src="../js/menu.js"></script>
    <script src="../js/calendario.js"></script>
</body>

</html>