<?php
declare(strict_types=1);

// Aseguramos que la clase DB esté disponible
require_once __DIR__ . '/db.php';

// Función para buscar un curso específico (la usa certificado.php)
function buscarCurso(int $id): ?array {
    try {
        $pdo = DB::getConnection();
        $sql = "SELECT id, nombre, estado, fecha_fin FROM cursos WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        
        $resultado = $stmt->fetch(); 
        return $resultado ?: null;
        
    } catch (PDOException $e) {
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