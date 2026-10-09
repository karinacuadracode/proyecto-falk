<?php
declare(strict_types=1);

// Aseguramos que la clase DB esté disponible
require_once __DIR__ . '/db.php';

// Función para buscar un curso específico (la usa certificado.php)
function buscarCurso(int $id, int $user_id): ?array {
    try {
        $pdo = DB::getConnection();
        // RN-04: Filtramos por ID del curso Y por el ID del usuario
        $sql = "SELECT id, nombre, estado, fecha_fin FROM cursos WHERE id = ? AND user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id, $user_id]);
        $curso = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $curso ?: null;
    } catch (PDOException $e) {
        error_log("Error al buscar curso: " . $e->getMessage());
        return null;
    }
}

function obtenerCursosDeUsuario(int $user_id): array {
    try {
        $pdo = DB::getConnection();
        $sql = "SELECT id, nombre, estado, fecha_fin FROM cursos WHERE user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id]);
        
        return $stmt->fetchAll(); 
        
    } catch (PDOException $e) {
        return [];
    }
}
?>