<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

// RF-25: estado de las cuentas de usuario.
// Una cuenta se bloquea sola después de esta cantidad de contraseñas incorrectas seguidas.
const MAX_INTENTOS_FALLIDOS = 5;

const ESTADOS_CUENTA = ['activo', 'bloqueado', 'inactivo'];

function listarUsuarios(): array {
    return DB::getConnection()
        ->query('SELECT ID, Username, Email, Role, Estado, Intentos_Fallidos FROM users ORDER BY Username')
        ->fetchAll();
}

// Cambia el estado de una cuenta. Al activarla se reinician los intentos fallidos.
function cambiarEstadoUsuario(int $id, string $estado): bool {
    if (!in_array($estado, ESTADOS_CUENTA, true)) {
        return false;
    }
    $stmt = DB::getConnection()->prepare(
        'UPDATE users SET Estado = :estado, Intentos_Fallidos = IF(:estado2 = \'activo\', 0, Intentos_Fallidos) WHERE ID = :id'
    );
    $stmt->execute([':estado' => $estado, ':estado2' => $estado, ':id' => $id]);
    return $stmt->rowCount() > 0;
}

// Suma un intento fallido y bloquea la cuenta si llega al máximo.
function registrarContrasenaIncorrecta(int $id): void {
    $stmt = DB::getConnection()->prepare(
        'UPDATE users
         SET Intentos_Fallidos = LEAST(Intentos_Fallidos + 1, 255),
             Estado = IF(Estado = \'activo\' AND Intentos_Fallidos >= :max, \'bloqueado\', Estado)
         WHERE ID = :id'
    );
    $stmt->execute([':max' => MAX_INTENTOS_FALLIDOS, ':id' => $id]);
}

function reiniciarIntentosFallidos(int $id): void {
    $stmt = DB::getConnection()->prepare('UPDATE users SET Intentos_Fallidos = 0 WHERE ID = :id AND Intentos_Fallidos > 0');
    $stmt->execute([':id' => $id]);
}

// RF-27: elimina una cuenta. Sus cursos se borran con ella (ON DELETE CASCADE) y sus
// registros de acceso se conservan sin ID (ON DELETE SET NULL). Devuelve el nombre o null.
function eliminarUsuario(int $id): ?string {
    $db = DB::getConnection();
    $stmt = $db->prepare('SELECT Username FROM users WHERE ID = :id');
    $stmt->execute([':id' => $id]);
    $nombre = $stmt->fetchColumn();
    if ($nombre === false) {
        return null;
    }
    $db->prepare('DELETE FROM users WHERE ID = :id')->execute([':id' => $id]);
    return $nombre;
}
