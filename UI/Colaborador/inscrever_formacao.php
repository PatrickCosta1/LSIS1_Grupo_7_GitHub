<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Não autenticado.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$formacao_nome = $data['formacao_nome'] ?? '';

if ($formacao_nome) {
    require_once '../../BLL/Colaborador/BLL_inscricoes.php';
    $inscricoesManager = new InscricoesManager();
    $inscricoesManager->inscrever($_SESSION['user_id'], $formacao_nome);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Dados em falta.']);
}