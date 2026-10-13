<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/session.php';
header('Content-Type: application/json; charset=utf-8');

function responder(int $codigo, bool $ok, string $mensaje, ?string $redireccion = null): void {
    http_response_code($codigo);
    echo json_encode(['success' => $ok, 'message' => $mensaje, 'redirect' => $redireccion]);
    exit;
}

// RF-24 / RN-09: registra cada intento en audit_log con el ID de usuario (si existe),
// lo que se escribió, el resultado y la IP. La fecha y hora la pone la base.
function registrarIntento(PDO $db, string $identificador, string $resultado, ?int $userId = null): void {
    $stmt = $db->prepare('INSERT INTO audit_log (User_ID, User_Email, Result, IP_Address) VALUES (:uid, :id, :res, :ip)');
    $stmt->execute([
        ':uid' => $userId,
        ':id'  => mb_substr($identificador, 0, 255),
        ':res' => $resultado,
        ':ip'  => $_SERVER['REMOTE_ADDR'] ?? null,
    ]);
}

// 1. Solo POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(405, false, 'Método no permitido.');
}

// 2. Se puede ingresar con usuario o con email.
$identificador = trim($_POST['usuario'] ?? '');
$contrasena    = $_POST['contrasena'] ?? '';

try {
    $db = DB::getConnection();

    // RN-09: un intento con campos vacíos también queda registrado.
    if ($identificador === '' || $contrasena === '') {
        registrarIntento($db, $identificador, 'FALLIDO');
        responder(422, false, 'Completá usuario y contraseña.');
    }

    // 3. Sentencia preparada (evita SQL injection).
    $stmt = $db->prepare('SELECT ID, Username, Password, Role FROM users WHERE Username = :u OR Email = :e LIMIT 1');
    $stmt->execute([':u' => $identificador, ':e' => strtolower($identificador)]);
    $usuario = $stmt->fetch();

    // 4. Si el usuario no existe, igual se ejecuta password_verify con un hash falso,
    //    así la respuesta tarda lo mismo y no revela qué usuarios existen.
    $hashFalso = '$2y$10$FwqVyAD9Tzc/7GLZlNn3EewXYayFzpsbrxLaVQ313ZhAKLcK5k6oC';
    $hash = $usuario['Password'] ?? $hashFalso;
    $valida = password_verify($contrasena, $hash);

    // RF-24: si el usuario existe se guarda su ID aunque la contraseña sea incorrecta.
    $userId = $usuario ? (int) $usuario['ID'] : null;

    if (!$usuario || !$valida) {
        registrarIntento($db, $identificador, 'FALLIDO', $userId);
        responder(401, false, 'Usuario o contraseña incorrectos.'); // mensaje genérico
    }

    // RN-09: se registra ANTES de crear la sesión; si el registro falla, no se inicia sesión.
    registrarIntento($db, $identificador, 'EXITOSO', $userId);

    // 5. Login correcto: sesión segura (RN-04).
    iniciarSesionSegura();
    session_regenerate_id(true);           // ID nuevo al iniciar sesión (evita session fixation)
    $_SESSION['user_id'] = $usuario['ID'];
    $_SESSION['username'] = $usuario['Username'];
    $_SESSION['role'] = $usuario['Role'];
    $_SESSION['ultima_actividad'] = time();
} catch (PDOException $e) {
    error_log('Login FALK: ' . $e->getMessage());
    responder(500, false, 'Ocurrió un error. Intentá más tarde.');
}

responder(200, true, 'Bienvenida/o.', 'pages-back/home.php');