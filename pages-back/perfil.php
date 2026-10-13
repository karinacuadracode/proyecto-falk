<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/db.php';
requerirSesion();

// RF-12: datos de la cuenta del usuario logueado
// Si la consulta falla, $usuario queda en false y la página muestra el aviso de error.
try {
    $stmt = DB::getConnection()->prepare('SELECT Username, Email, Role, Fecha_Nacimiento FROM users WHERE ID = :id LIMIT 1');
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $usuario = $stmt->fetch();
} catch (PDOException $e) {
    error_log('Perfil FALK: ' . $e->getMessage());
    $usuario = false;
}

function e(string $t): string { return htmlspecialchars($t, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>FALK | Mi perfil</title>
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

        <a href="perfil.php" class="boton-icono" aria-label="Mi perfil" aria-current="page">
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
        <section class="perfil" aria-labelledby="titulo-perfil">
            <h1 id="titulo-perfil" class="perfil-titulo">Mi perfil</h1>

            <?php if ($usuario): ?>
                <article class="tarjeta-perfil">
                    <div class="tarjeta-perfil-banner" aria-hidden="true"></div>

                    <!-- avatar con la inicial del usuario -->
                    <div class="avatar" aria-hidden="true"><?= e(strtoupper(substr($usuario['Username'], 0, 1))) ?></div>

                    <p class="perfil-nombre"><?= e($usuario['Username']) ?></p>
                    <span class="etiqueta-rol"><?= e(ucfirst($usuario['Role'])) ?></span>

                    <dl class="datos-perfil">
                        <div class="dato-perfil">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="8" r="4"/>
                                <path d="M4 21c0-4 3.6-6.5 8-6.5s8 2.5 8 6.5"/>
                            </svg>
                            <div>
                                <dt>Usuario</dt>
                                <dd><?= e($usuario['Username']) ?></dd>
                            </div>
                        </div>
                        <div class="dato-perfil">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                                <polyline points="3 7 12 13 21 7"/>
                            </svg>
                            <div>
                                <dt>Email</dt>
                                <dd><?= e($usuario['Email']) ?></dd>
                            </div>
                        </div>
                        <div class="dato-perfil">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 3l8 3v6c0 4.5-3.4 8.2-8 9-4.6-.8-8-4.5-8-9V6z"/>
                                <polyline points="9 12 11 14 15 10"/>
                            </svg>
                            <div>
                                <dt>Rol</dt>
                                <dd><?= e(ucfirst($usuario['Role'])) ?></dd>
                            </div>
                        </div>
                        <?php if (!empty($usuario['Fecha_Nacimiento'])): // RF-26: solo si la cargó al registrarse ?>
                            <div class="dato-perfil">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="5" width="18" height="16" rx="2"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                    <line x1="8" y1="3" x2="8" y2="7"/>
                                    <line x1="16" y1="3" x2="16" y2="7"/>
                                </svg>
                                <div>
                                    <dt>Fecha de nacimiento</dt>
                                    <dd><?= e(date('d/m/Y', strtotime($usuario['Fecha_Nacimiento']))) ?></dd>
                                </div>
                            </div>
                        <?php endif; ?>
                    </dl>
                </article>
            <?php else: ?>
                <p class="perfil-error" role="alert">No se pudieron cargar los datos de la cuenta.</p>
            <?php endif; ?>

            <a href="home.php" class="boton-volver">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
                Volver a Home
            </a>
        </section>
    </main>

    <script src="../js/menu.js"></script>
</body>
</html>