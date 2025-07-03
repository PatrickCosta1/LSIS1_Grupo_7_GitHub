<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Não autenticado']);
    exit;
}

require_once '../../BLL/Colaborador/BLL_formacoes.php';
require_once '../../BLL/Colaborador/BLL_ficha_colaborador.php';

$colabBLL = new ColaboradorFichaManager();
$colab = $colabBLL->getColaboradorByUserId($_SESSION['user_id']);
$colaborador_id = $colab['id'] ?? null;

if (!$colaborador_id) {
    echo json_encode(['success' => false, 'error' => 'Colaborador não encontrado']);
    exit;
}

$formacao_id = $_POST['formacao_id'] ?? null;
if (!$formacao_id) {
    echo json_encode(['success' => false, 'error' => 'ID da formação não fornecido']);
    exit;
}

$formacoesBLL = new FormacoesManager();
$resultado = $formacoesBLL->inscrever($colaborador_id, $formacao_id);

if ($resultado) {
    echo json_encode(['success' => true, 'message' => 'Inscrição realizada com sucesso!']);
} else {
    echo json_encode(['success' => false, 'error' => 'Erro ao realizar inscrição']);
}
?>