<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Não autenticado.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$pw_atual = $data['atual'] ?? '';
$pw_nova = $data['nova'] ?? '';

if (!$pw_atual || !$pw_nova) {
    echo json_encode(['success' => false, 'error' => 'Dados em falta.']);
    exit;
}

require_once '../../BLL/Comuns/BLL_perfil.php';
$perfilManager = new PerfilManager();

$result = $perfilManager->alterarPassword($_SESSION['user_id'], $pw_atual, $pw_nova);

if ($result === true) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $result]);
}