<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'coordenador') {
    http_response_code(403);
    echo json_encode(['error' => 'Acesso negado']);
    exit();
}

require_once '../../BLL/Coordenador/BLL_dashboard_coordenador.php';

$coordBLL = new CoordenadorDashboardManager();
$userId = $_SESSION['user_id'];
$ano = isset($_GET['ano']) ? intval($_GET['ano']) : date('Y');

try {
    // Buscar equipas do coordenador
    $equipas = $coordBLL->getEquipasByCoordenador($userId);
    $equipas_labels = [];
    foreach ($equipas as $e) {
        $equipas_labels[] = $e['nome'];
    }
    
    // Buscar dados de retenção para o ano selecionado
    $retencao_por_equipa_assoc = [];
    $retencao_global = null;
    
    if (method_exists($coordBLL, 'getTaxaRetencaoPorEquipa')) {
        $retencao_por_equipa_assoc = $coordBLL->getTaxaRetencaoPorEquipa($userId, $ano);
    }
    if (method_exists($coordBLL, 'getTaxaRetencaoGlobal')) {
        $retencao_global = $coordBLL->getTaxaRetencaoGlobal($userId, $ano);
    }
    
    // Garantir que a ordem dos arrays corresponde à ordem de $equipas_labels
    $retencao_por_equipa = [];
    foreach ($equipas_labels as $nome_equipa) {
        $retencao_por_equipa[] = isset($retencao_por_equipa_assoc[$nome_equipa]) ? $retencao_por_equipa_assoc[$nome_equipa] : 0;
    }
    
    echo json_encode([
        'success' => true,
        'retencao_por_equipa' => $retencao_por_equipa,
        'retencao_global' => $retencao_global,
        'ano' => $ano
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro interno do servidor: ' . $e->getMessage()]);
}
?>
