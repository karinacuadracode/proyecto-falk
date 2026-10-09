<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Tus cursos y tu calendario académico en FALK, plataforma de aprendizaje online.">
    <meta name="keywords" content="FALK, mis cursos, calendario académico, aprendizaje online">
    <meta name="robots" content="noindex, nofollow">

    <title>FALK | Mis cursos</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <!--CSS-->
    <link rel="stylesheet" href="../styles/styles.css">
</head>

<body>
    <header class="barra-superior">
        <button type="button" class="boton-icono" id="boton-menu" aria-label="Abrir menú" aria-expanded="false"
            aria-controls="menu-principal">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                aria-hidden="true">
                <line x1="3" y1="6" x2="21" y2="6" />
                <line x1="3" y1="12" x2="21" y2="12" />
                <line x1="3" y1="18" x2="21" y2="18" />
            </svg>
        </button>

        <img src="../pictures/logo-falk.png" alt="FALK - Plataforma de aprendizaje online" class="logo">

        <a href="perfil.php" class="boton-icono" aria-label="Mi perfil">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <circle cx="12" cy="7" r="4.5" />
                <path d="M3 21c0-4.4 4-7 9-7s9 2.6 9 7z" />
            </svg>
        </a>
    </header>

    <nav id="menu-principal" class="menu-lateral" aria-label="Menú principal" hidden>
        <ul>
            <li><a href="home.php" aria-current="page">Home</a></li>
            <li><a href="mis-cursos.php">Mis cursos</a></li>
            <li><a href="calendario.php">Calendario</a></li>
            <li><a href="../controllers/logout-controller.php">Cerrar sesión</a></li>
        </ul>
    </nav>

    <main class="contenedor-principal">
        <section class="seccion-home">
            <h2>Mis cursos</h2>

            <div class="contenedor-cursos">
                <?php
                $contador = 0;

                if (empty($cursos_del_usuario)) {
                    echo "<p style='grid-column: 1 / -1; text-align: center; color: #666; margin-top: 20px;'>No estás inscripto en ningún curso todavía.</p>";
                } else {
                    foreach ($cursos_del_usuario as $curso) {
                        $contador++;

                        // Lógica para ocultar los cursos a partir del tercero
                        $clasesCss = "tarjeta-curso";
                        $estiloOculto = "";
                        if ($contador > 2) {
                            $clasesCss .= " curso-extra";
                            $estiloOculto = 'style="display: none;"';
                        }

                        // Textos y enlaces dinámicos según el estado
                        $estadoCss = ($curso['estado'] === 'finalizado') ? 'finalizado' : 'en-progreso';
                        $estadoTexto = ($curso['estado'] === 'finalizado') ? 'Finalizado' : 'En progreso';

                        $enlace = ($curso['estado'] === 'finalizado')
                            ? "certificado.php?curso=" . urlencode($curso['id'])
                            : "../pages-front/leccion-mock.html?curso=" . urlencode(strtolower(str_replace(' ', '-', $curso['nombre'])));

                        $textoEnlace = ($curso['estado'] === 'finalizado') ? "Visualizar certificado" : "Continuar desde última lección";
                ?>

                        <article class="<?php echo $clasesCss; ?>" <?php echo $estiloOculto; ?>>
                            <h3><?php echo htmlspecialchars($curso['nombre']); ?></h3>
                            <p class="estado-curso <?php echo $estadoCss; ?>"><?php echo $estadoTexto; ?></p>
                            <a href="<?php echo $enlace; ?>" class="accion-curso"><?php echo $textoEnlace; ?> <span aria-hidden="true">→</span></a>
                        </article>

                <?php
                    }
                }
                ?>

                <?php if (!empty($cursos_del_usuario) && count($cursos_del_usuario) > 2): ?>
                    <button type="button" id="btn-mostrar-mas" class="boton-icono" aria-label="Ver más cursos" style="transition: transform 0.3s ease;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                            <polyline points="6 9 12 15 18 9" />
                        </svg>
                    </button>
                <?php endif; ?>
            </div>
        </section>

        <section class="seccion-home">
            <h2>Calendario</h2>

            <a href="calendario.php" class="calendario" aria-label="Ver calendario">
                <svg viewBox="0 0 64 64" fill="currentColor" aria-hidden="true">
                    <rect x="6" y="10" width="52" height="48" rx="8" />
                    <rect x="11" y="24" width="42" height="29" rx="2" fill="#FFFFFF" />
                    <rect x="17" y="4" width="5" height="12" rx="2.5" />
                    <rect x="42" y="4" width="5" height="12" rx="2.5" />
                    <rect x="15" y="28" width="6" height="6" rx="1" />
                    <rect x="25" y="28" width="6" height="6" rx="1" />
                    <rect x="35" y="28" width="6" height="6" rx="1" />
                    <rect x="45" y="28" width="6" height="6" rx="1" />
                    <rect x="15" y="37" width="6" height="6" rx="1" />
                    <rect x="25" y="37" width="6" height="6" rx="1" />
                    <rect x="45" y="37" width="6" height="6" rx="1" />
                    <rect x="15" y="46" width="6" height="5" rx="1" />
                    <rect x="25" y="46" width="6" height="5" rx="1" />
                    <rect x="35" y="46" width="6" height="5" rx="1" />
                    <polyline points="34 40 37 43 43 36" fill="none" stroke="currentColor" stroke-width="3"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>
        </section>
    </main>
    <!-- Script para que funcione el menú -->
    <script src="../js/menu.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const btnMostrarMas = document.getElementById("btn-mostrar-mas");
            // Seleccionamos todas las tarjetas que tengan la clase .curso-extra
            const cursosExtra = document.querySelectorAll(".curso-extra");

            if (btnMostrarMas && cursosExtra.length > 0) {
                btnMostrarMas.addEventListener("click", function() {
                    let estanOcultos = cursosExtra[0].style.display === "none";

                    cursosExtra.forEach(function(curso) {
                        if (estanOcultos) {
                            curso.style.display = ""; // Le quita el display:none para que vuelva a su diseño original
                        } else {
                            curso.style.display = "none"; // Los oculta
                        }
                    });

                    // Gira la flecha
                    if (estanOcultos) {
                        btnMostrarMas.style.transform = "rotate(180deg)";
                    } else {
                        btnMostrarMas.style.transform = "rotate(0deg)";
                    }
                });
            }
        });
    </script>
</body>

</html>