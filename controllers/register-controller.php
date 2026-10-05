<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/session.php';
header('Content-Type: application/json; charset=utf-8');

// Corta la ejecución y responde siempre con el mismo formato JSON.
function responder(int $codigo, bool $ok, string $mensaje, ?string $redireccion = null): void {
    http_response_code($codigo);
    echo json_encode(['success' => $ok, 'message' => $mensaje, 'redirect' => $redireccion]);
    exit;
}

// 1. Solo aceptamos POST, para que los datos nunca viajen en la URL.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(405, false, 'Método no permitido.');
}

// 2. Leemos SOLO los campos que esperamos. Si alguien manda "role", se ignora.
$usuario    = trim($_POST['usuario'] ?? '');
$email      = strtolower(trim($_POST['email'] ?? ''));
$contrasena = $_POST['contrasena'] ?? '';

// 3. Validación en el servidor con lista blanca: definimos lo permitido y rechazamos el resto.
if (!preg_match('/^[a-zA-Z0-9._]{3,30}$/', $usuario)) {
    responder(422, false, 'El usuario debe tener entre 3 y 30 caracteres: letras, números, punto o guion bajo.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
    responder(422, false, 'Ingresá un email válido.');
}

// Política de contraseña (RN-07: mínimo 8 caracteres, al menos una mayúscula, un número y un símbolo).
if (mb_strlen($contrasena) < 8 || strlen($contrasena) > 72
    || !preg_match('/[A-Z]/', $contrasena)
    || !preg_match('/[0-9]/', $contrasena)
    || !preg_match('/[^a-zA-Z0-9]/', $contrasena)) {
    responder(422, false, 'La contraseña debe tener al menos 8 caracteres, con una mayúscula, un número y un símbolo.');
}

// 4. Hash con bcrypt (RNF-03). Nunca guardamos la contraseña en texto plano.
$hash = password_hash($contrasena, PASSWORD_DEFAULT);

// 5. Sentencia preparada: los datos viajan separados del SQL, así no hay SQL injection.
//    No insertamos Role: la base pone 'usuario' por defecto.
try {
    $db = DB::getConnection();
    $stmt = $db->prepare('INSERT INTO users (Username, Email, Password) VALUES (:usuario, :email, :hash)');
    $stmt->execute([
        ':usuario' => $usuario,
        ':email'   => $email,
        ':hash'    => $hash,
    ]);
    $nuevoId = (int) $db->lastInsertId();
} catch (PDOException $e) {
    // 1062 = clave duplicada (RN-01 / RN-06). El mensaje no dice cuál dato está repetido.
    if (($e->errorInfo[1] ?? null) === 1062) {
        responder(409, false, 'No pudimos crear la cuenta con esos datos. Probá con otro usuario o email.');
    }
    error_log('Registro FALK: ' . $e->getMessage()); // el detalle va al log, no a la pantalla
    responder(500, false, 'Ocurrió un error. Intentá más tarde.');
}

// 6. RF-09: al registrarse, el usuario queda logueado y entra a Home (misma sesión segura que el login, RN-04).
iniciarSesionSegura();
session_regenerate_id(true);
$_SESSION['user_id'] = $nuevoId;
$_SESSION['username'] = $usuario;
$_SESSION['role'] = 'usuario';
$_SESSION['ultima_actividad'] = time();

responder(201, true, 'Cuenta creada. ¡Bienvenido/a a FALK!', '../pages-back/home.php');