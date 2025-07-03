<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Sessão expirada.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['data_inicio'], $data['data_fim'])) {
    echo json_encode(['success' => false, 'error' => 'Dados em falta.']);
    exit;
}

require_once '../../BLL/Colaborador/BLL_ficha_colaborador.php';
require_once '../../BLL/Comuns/BLL_notificacoes.php';

$colabBLL = new ColaboradorFichaManager();
$notBLL = new NotificacoesManager();
$colab = $colabBLL->getColaboradorByUserId($_SESSION['user_id']);
$dataInicio = $data['data_inicio'];
$dataFim = $data['data_fim'];

if ($dataInicio > $dataFim) {
    echo json_encode(['success' => false, 'error' => 'A data de início não pode ser posterior à data de fim.']);
    exit;
}

if ($colabBLL->criarPedidoFerias($colab['id'], $dataInicio, $dataFim)) {
    $notBLL->notificarRH("Novo pedido de férias de " . htmlspecialchars($colab['nome']) . " de $dataInicio até $dataFim.");
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Erro ao submeter pedido de férias.']);
}