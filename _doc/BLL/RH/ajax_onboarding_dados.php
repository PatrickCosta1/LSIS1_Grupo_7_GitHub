<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['profile'] !== 'rh') {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['erro' => 'Acesso negado']);
    exit();
}

require_once '../../BLL/RH/BLL_colaboradores_gerir.php';
$colabBLL = new RHColaboradoresManager();

$token = $_GET['token'] ?? '';
$dados = [];

if ($token) {
    $onboarding = $colabBLL->getOnboardingTempByToken($token);
    if ($onboarding && !empty($onboarding['dados_json'])) {
        $json = json_decode($onboarding['dados_json'], true);
        if (is_array($json)) {
            $dados = $json;
        }
    }
}

header('Content-Type: application/json');
echo json_encode(['dados' => $dados]);
?>