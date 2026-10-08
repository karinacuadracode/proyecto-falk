<?php
declare(strict_types=1);

// Cursos de ejemplo hasta que exista la tabla de cursos (RF-15).
// Cuando esté la base, reemplazar buscarCurso() por una consulta con los cursos del usuario logueado.
const CURSOS_MOCK = [
    1 => ['nombre' => 'Introducción a la programación', 'estado' => 'en_progreso', 'fecha_fin' => null],
    2 => ['nombre' => 'Diseño web responsive',          'estado' => 'finalizado',  'fecha_fin' => '2026-09-30'],
];

function buscarCurso(int $id): ?array {
    return CURSOS_MOCK[$id] ?? null;
}