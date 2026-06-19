<?php
require_once 'config/conexao.php';

header('Content-Type: application/json; charset=utf-8');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    echo json_encode(['sucesso' => false]);
    exit;
}

$conexao->prepare("UPDATE animes SET likes = likes + 1 WHERE id = ?")->execute([$id]);

$stmt = $conexao->prepare("SELECT likes FROM animes WHERE id = ?");
$stmt->execute([$id]);
$linha = $stmt->fetch();

if (!$linha) {
    http_response_code(404);
    echo json_encode(['sucesso' => false]);
    exit;
}

echo json_encode([
    'sucesso' => true,
    'likes'   => (int) $linha['likes'],
]);
