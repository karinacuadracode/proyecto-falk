<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/cursos-mock.php';
requerirSesion();

$usuario_id = $_SESSION['user_id']; 
$cursos_del_usuario = obtenerCursosDeUsuario($usuario_id);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FALK | Mis cursos</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <header class="barra-superior">
        <button type="button" class="boton-icono" id="boton-menu" aria-label="Abrir menú">
            <!-- (Icono SVG Menú) -->
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
        <img src="../pictures/logo-falk.png" alt="FALK" class="logo">
        <a href="perfil.php" class="boton-icono" aria-label="Mi perfil">
            <!-- (Icono SVG Perfil) -->
            <svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="7" r="4.5"/><path d="M3 21c0-4.4 4-7 9-7s9 2.6 9 7z"/></svg>
        </a>
    </header>

    <nav id="menu-principal" class="menu-lateral" aria-label="Menú principal" hidden>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="mis-cursos.php" aria-current="page">Mis cursos</a></li>
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
                    echo "<p>No estás inscripto en ningún curso todavía.</p>";
                } else {
                    foreach ($cursos_del_usuario as $curso) {
                        $contador++;
                        
                        $clasesCss = "tarjeta-curso";
                        
                        if ($contador > 2) {
                            $clasesCss .= " curso-extra oculto"; 
                        }
                        
                        $estadoCss = ($curso['estado'] === 'finalizado') ? 'finalizado' : 'en-progreso';
                        $estadoTexto = ($curso['estado'] === 'finalizado') ? 'Finalizado' : 'En progreso';
                        
                        $enlace = ($curso['estado'] === 'finalizado') 
                            ? "certificado.php?curso=" . urlencode($curso['id']) 
                            : "../pages-front/leccion-mock.html?curso=" . urlencode(strtolower(str_replace(' ', '-', $curso['nombre'])));
                        
                        $textoEnlace = ($curso['estado'] === 'finalizado') ? "Visualizar certificado" : "Continuar desde última lección";
                        ?>

                        <article class="<?php echo $clasesCss; ?>">
                            <h3><?php echo htmlspecialchars($curso['nombre']); ?></h3>
                            <p class="estado-curso <?php echo $estadoCss; ?>"><?php echo $estadoTexto; ?></p>
                            <a href="<?php echo $enlace; ?>" class="accion-curso"><?php echo $textoEnlace; ?> <span aria-hidden="true">→</span></a>
                        </article>
                    <?php 
                    }
                }
                ?>

                <?php if (!empty($cursos_del_usuario) && count($cursos_del_usuario) > 2): ?>
                <!-- Sacamos el transition inline y agregamos clase boton-desplegable -->
                <button type="button" id="btn-mostrar-mas" class="boton-icono boton-desplegable" aria-label="Ver más cursos">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <?php endif; ?>
            </div>
        </section>
    </main>
    
    <script src="../js/menu.js"></script>
    <!-- Llamamos al nuevo JS externo -->
    <script src="../js/cursos.js"></script>
</body>
</html>