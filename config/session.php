<?php
declare(strict_types=1);

// RN-04: tiempo máximo de inactividad (15 minutos, a confirmar con el equipo).
const TIEMPO_INACTIVIDAD = 15 * 60;

// Inicia la sesión con cookies seguras.
function iniciarSesionSegura(): void {
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    ini_set('session.use_strict_mode', '1');   // no acepta IDs de sesión inventados (evita session fixation)
    ini_set('session.use_only_cookies', '1');  // el ID nunca viaja en la URL
    ini_set('session.gc_maxlifetime', (string) TIEMPO_INACTIVIDAD);

    session_set_cookie_params([
        'lifetime' => 0,                        // la cookie se borra al cerrar el navegador
        'path'     => '/',
        'secure'   => !empty($_SERVER['HTTPS']), // solo por HTTPS cuando haya HTTPS (RNF-03)
        'httponly' => true,                     // JavaScript no puede leer la cookie (mitiga robo por XSS)
        'samesite' => 'Strict',                 // no se envía desde otros sitios (mitiga CSRF)
    ]);

    session_start();
}

// Borra todos los datos de la sesión y la cookie.
function cerrarSesion(): void {
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }

    session_destroy();
}

// Se llama al principio de cada página protegida.
function requerirSesion(): void {
    iniciarSesionSegura();

    // Sin sesión iniciada: al login.
    if (empty($_SESSION['user_id'])) {
        header('Location: ../index.html');
        exit;
    }

    // RN-04: si pasó más tiempo que el permitido desde la última actividad, se cierra la sesión.
    $ahora = time();
    if (isset($_SESSION['ultima_actividad']) && ($ahora - $_SESSION['ultima_actividad']) > TIEMPO_INACTIVIDAD) {
        cerrarSesion();
        header('Location: ../index.html?motivo=inactividad');
        exit;
    }

    // Hubo actividad: se reinicia el contador.
    $_SESSION['ultima_actividad'] = $ahora;
}