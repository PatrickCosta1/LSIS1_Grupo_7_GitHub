<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Não autenticado.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$data_inicio = $data['data_inicio'] ?? '';
$data_fim = $data['data_fim'] ?? '';

if ($data_inicio && $data_fim) {
    require_once '../../BLL/Colaborador/BLL_ferias.php';
    $feriasManager = new FeriasManager();
    $ok = $feriasManager->pedirFerias($_SESSION['user_id'], $data_inicio, $data_fim);
    if ($ok) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Erro ao registar pedido.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Dados em falta.']);
}