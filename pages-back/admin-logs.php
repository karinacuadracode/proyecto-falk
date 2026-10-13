<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/db.php';
requerirAdmin(); // RN-08: solo administradores

// RF-23: listado de accesos registrados en audit_log.
// RNF-02 / RNF-06: se pagina en la base para que la pantalla cargue rápido aunque haya miles de registros.
const ACCESOS_POR_PAGINA = 20;

$pagina = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: 1;
$accesos = [];
$totalPaginas = 1;
$error = false;

try {
    $db = DB::getConnection();

    $total = (int) $db->query('SELECT COUNT(*) FROM audit_log')->fetchColumn();
    $totalPaginas = max(1, (int) ceil($total / ACCESOS_POR_PAGINA));
    $pagina = min($pagina, $totalPaginas);

    $stmt = $db->prepare('SELECT User_Email, Attempt_Time, Result, IP_Address
                          FROM audit_log
                          ORDER BY Attempt_Time DESC, Log_ID DESC
                          LIMIT :limite OFFSET :desde');
    $stmt->bindValue(':limite', ACCESOS_POR_PAGINA, PDO::PARAM_INT);
    $stmt->bindValue(':desde', ($pagina - 1) * ACCESOS_POR_PAGINA, PDO::PARAM_INT);
    $stmt->execute();
    $accesos = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log('Listado de accesos FALK: ' . $e->getMessage());
    $error = true;
}

function e(?string $t): string { return htmlspecialchars($t ?? '', ENT_QUOTES, 'UTF-8'); }

function formatearFecha(?string $fecha): string {
    $d = $fecha ? DateTime::createFromFormat('Y-m-d H:i:s', $fecha) : false;
    return $d ? $d->format('d/m/Y H:i:s') : '-';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>FALK | Listado de accesos</title>
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
            <li><a href="admin-logs.php" aria-current="page">Listado de accesos</a></li>
            <li><a href="../controllers/logout-controller.php">Cerrar sesión</a></li>
        </ul>
    </nav>

    <main class="contenedor-principal">
        <section class="accesos" aria-labelledby="titulo-accesos">
            <h1 id="titulo-accesos" class="accesos-titulo">Listado de accesos</h1>

            <?php if ($error): ?>
                <p class="accesos-mensaje accesos-error" role="alert">No se pudo cargar el listado de accesos.</p>
            <?php elseif (empty($accesos)): ?>
                <p class="accesos-mensaje">Todavía no hay accesos registrados.</p>
            <?php else: ?>
                <!-- tabindex: en celular la tabla se desplaza de costado y se puede recorrer con el teclado -->
                <div class="tabla-accesos-contenedor" tabindex="0" role="region" aria-labelledby="titulo-accesos">
                    <table class="tabla-accesos">
                        <caption class="sr-only">Accesos registrados, del más reciente al más antiguo. Página <?= $pagina ?> de <?= $totalPaginas ?>.</caption>
                        <thead>
                            <tr>
                                <th scope="col">Fecha y hora</th>
                                <th scope="col">Usuario</th>
                                <th scope="col">Resultado</th>
                                <th scope="col">IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($accesos as $acceso):
                                $exitoso = $acceso['Result'] === 'EXITOSO'; ?>
                                <tr>
                                    <td><?= e(formatearFecha($acceso['Attempt_Time'])) ?></td>
                                    <td class="acceso-usuario"><?= e($acceso['User_Email']) ?></td>
                                    <td>
                                        <span class="estado-acceso <?= $exitoso ? 'exitoso' : 'fallido' ?>">
                                            <?= $exitoso ? 'Exitoso' : 'Fallido' ?>
                                        </span>
                                    </td>
                                    <td><?= e($acceso['IP_Address'] ?: '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($totalPaginas > 1): ?>
                    <nav class="paginacion" aria-label="Páginas del listado de accesos">
                        <?php if ($pagina > 1): ?>
                            <a href="?pagina=<?= $pagina - 1 ?>" class="paginacion-enlace">
                                <span aria-hidden="true">←</span> Más recientes
                            </a>
                        <?php endif; ?>
                        <p class="paginacion-estado">Página <?= $pagina ?> de <?= $totalPaginas ?></p>
                        <?php if ($pagina < $totalPaginas): ?>
                            <a href="?pagina=<?= $pagina + 1 ?>" class="paginacion-enlace">
                                Más antiguos <span aria-hidden="true">→</span>
                            </a>
                        <?php endif; ?>
                    </nav>
                <?php endif; ?>
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
