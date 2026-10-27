<?php
require_once __DIR__ . '/includes/db.php';
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false]);
    exit;
}
$datos = json_decode(file_get_contents('php://input'), true);
$puntos = filter_var($datos['puntos'] ?? null, FILTER_VALIDATE_INT);
$total = filter_var($datos['total'] ?? null, FILTER_VALIDATE_INT);
if ($puntos === false || $total !== 3 || $puntos < 0 || $puntos > $total) {
    http_response_code(422);
    echo json_encode(['ok' => false]);
    exit;
}
try {
    $stmt = getPDO()->prepare('INSERT INTO resultados_quiz (puntos, total) VALUES (?, ?)');
    $stmt->execute([$puntos, $total]);
    echo json_encode(['ok' => true]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false]);
}
