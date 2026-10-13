<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/usuarios.php';
requerirAdmin(); // RN-08: solo administradores

// RF-25: activar / desbloquear (y desactivar) cuentas de usuario. RF-27: eliminar cuentas.
// Las acciones llegan por POST con token CSRF; después se redirige (así F5 no repite la acción).
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: 0;
    $estado = $_POST['estado'] ?? '';
    $accion = $_POST['accion'] ?? 'estado';

    if (!csrfValido($_POST['csrf'] ?? null)) {
        $_SESSION['aviso'] = ['error', 'La acción expiró. Volvé a intentarlo.'];
    } elseif ($accion === 'eliminar') {
        if ($id === (int) $_SESSION['user_id']) {
            $_SESSION['aviso'] = ['error', 'No podés eliminar tu propia cuenta.'];
        } else {
            try {
                $nombre = eliminarUsuario($id);
                $_SESSION['aviso'] = $nombre !== null
                    ? ['exito', 'Se eliminó la cuenta de ' . $nombre . '.']
                    : ['error', 'Esa cuenta ya no existe.'];
            } catch (PDOException $e) {
                error_log('Eliminar usuario FALK: ' . $e->getMessage());
                $_SESSION['aviso'] = ['error', 'No se pudo eliminar la cuenta.'];
            }
        }
    } elseif ($id === (int) $_SESSION['user_id'] && $estado !== 'activo') {
        $_SESSION['aviso'] = ['error', 'No podés desactivar tu propia cuenta.'];
    } else {
        try {
            $ok = cambiarEstadoUsuario($id, $estado);
            $_SESSION['aviso'] = $ok
                ? ['exito', $estado === 'activo' ? 'La cuenta quedó activa.' : 'La cuenta quedó desactivada.']
                : ['error', 'No se pudo cambiar el estado de la cuenta.'];
        } catch (PDOException $e) {
            error_log('Gestión de usuarios FALK: ' . $e->getMessage());
            $_SESSION['aviso'] = ['error', 'No se pudo cambiar el estado de la cuenta.'];
        }
    }

    header('Location: admin-usuarios.php');
    exit;
}

$aviso = $_SESSION['aviso'] ?? null;
unset($_SESSION['aviso']);

$usuarios = [];
$error = false;
try {
    $usuarios = listarUsuarios();
} catch (PDOException $e) {
    error_log('Gestión de usuarios FALK: ' . $e->getMessage());
    $error = true;
}

function e(?string $t): string { return htmlspecialchars($t ?? '', ENT_QUOTES, 'UTF-8'); }

$textosEstado = ['activo' => 'Activa', 'bloqueado' => 'Bloqueada', 'inactivo' => 'Inactiva'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>FALK | Gestión de usuarios</title>
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
            <li><a href="admin-logs.php">Listado de accesos</a></li>
            <li><a href="admin-usuarios.php" aria-current="page">Gestión de usuarios</a></li>
            <li><a href="../controllers/logout-controller.php">Cerrar sesión</a></li>
        </ul>
    </nav>

    <main class="contenedor-principal">
        <section class="accesos accesos-ancho" aria-labelledby="titulo-usuarios">
            <h1 id="titulo-usuarios" class="accesos-titulo">Gestión de usuarios</h1>

            <?php if ($aviso): ?>
                <p class="accesos-mensaje <?= $aviso[0] === 'error' ? 'accesos-error' : 'accesos-exito' ?>" role="status"><?= e($aviso[1]) ?></p>
            <?php endif; ?>

            <?php if ($error): ?>
                <p class="accesos-mensaje accesos-error" role="alert">No se pudo cargar el listado de usuarios.</p>
            <?php else: ?>
                <p class="usuarios-ayuda">Una cuenta se bloquea sola después de <?= MAX_INTENTOS_FALLIDOS ?> contraseñas incorrectas seguidas.</p>

                <div class="tabla-accesos-contenedor" tabindex="0" role="region" aria-labelledby="titulo-usuarios">
                    <table class="tabla-accesos">
                        <caption class="sr-only">Cuentas de usuario con su estado y las acciones disponibles.</caption>
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Usuario</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Acciones</th>
                                <th scope="col">Intentos fallidos</th>
                                <th scope="col">Rol</th>
                                <th scope="col">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $u):
                                $esYo = (int) $u['ID'] === (int) $_SESSION['user_id']; ?>
                                <tr>
                                    <td><?= (int) $u['ID'] ?></td>
                                    <td class="acceso-usuario"><?= e($u['Username']) ?></td>
                                    <td>
                                        <span class="estado-acceso estado-cuenta-<?= e($u['Estado']) ?>">
                                            <?= e($textosEstado[$u['Estado']] ?? $u['Estado']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($esYo): ?>
                                            <span class="usuarios-ayuda">Tu cuenta</span>
                                        <?php else: ?>
                                            <div class="acciones-usuario">
                                                <form method="post" class="form-accion-usuario">
                                                    <input type="hidden" name="csrf" value="<?= e(tokenCsrf()) ?>">
                                                    <input type="hidden" name="id" value="<?= (int) $u['ID'] ?>">
                                                    <?php if ($u['Estado'] !== 'activo'): ?>
                                                        <input type="hidden" name="estado" value="activo">
                                                        <button type="submit" class="boton-tabla">
                                                            <?= $u['Estado'] === 'bloqueado' ? 'Desbloquear' : 'Activar' ?>
                                                            <span class="sr-only"><?= e($u['Username']) ?></span>
                                                        </button>
                                                    <?php else: ?>
                                                        <input type="hidden" name="estado" value="inactivo">
                                                        <button type="submit" class="boton-tabla boton-tabla-secundario">
                                                            Desactivar <span class="sr-only"><?= e($u['Username']) ?></span>
                                                        </button>
                                                    <?php endif; ?>
                                                </form>

                                                <!-- RF-27: js/admin-usuarios.js pide confirmación antes de enviar -->
                                                <form method="post" class="form-accion-usuario" data-confirmar="¿Eliminar la cuenta de <?= e($u['Username']) ?>? Se borran también sus cursos. Esta acción no se puede deshacer.">
                                                    <input type="hidden" name="csrf" value="<?= e(tokenCsrf()) ?>">
                                                    <input type="hidden" name="id" value="<?= (int) $u['ID'] ?>">
                                                    <input type="hidden" name="accion" value="eliminar">
                                                    <button type="submit" class="boton-tabla boton-tabla-peligro">
                                                        Eliminar <span class="sr-only"><?= e($u['Username']) ?></span>
                                                    </button>
                                                </form>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= (int) $u['Intentos_Fallidos'] ?></td>
                                    <td><?= e(ucfirst($u['Role'])) ?></td>
                                    <td><?= e($u['Email']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
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
    <script src="../js/admin-usuarios.js"></script>
</body>
</html>
