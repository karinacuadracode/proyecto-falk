<?php
class DB {
    private static ?PDO $instance = null;

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $host = 'localhost';
            $db   = 'falk_db';
            $user = 'root';
            $pass = '';
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,                
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET SESSION sql_mode = 'STRICT_ALL_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'",
            ];

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                error_log('Conexión FALK: ' . $e->getMessage());
                errorBaseDeDatos();
            }
        }
        return self::$instance;
    }
}

// Si la base no responde: los controladores contestan JSON (los lee el JS) y las páginas muestran un aviso.
// El detalle del error queda en el log de PHP, nunca en pantalla.
function errorBaseDeDatos(): never {
    http_response_code(503);
    if (str_contains($_SERVER['SCRIPT_NAME'] ?? '', '/controllers/')) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => 'Ocurrió un error. Intentá más tarde.']);
    } else {
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8">'
           . '<meta name="viewport" content="width=device-width, initial-scale=1.0">'
           . '<title>FALK | Servicio no disponible</title>'
           . '<link rel="stylesheet" href="../styles/styles.css"></head><body>'
           . '<main class="contenedor-principal"><section class="certificado-seccion">'
           . '<div class="certificado-aviso" role="alert"><h1>Servicio no disponible</h1>'
           . '<p>No pudimos conectarnos con la plataforma. Intentá de nuevo en unos minutos.</p></div>'
           . '<a href="../index.html" class="boton-volver">Volver al inicio</a>'
           . '</section></main></body></html>';
    }
    exit;
}