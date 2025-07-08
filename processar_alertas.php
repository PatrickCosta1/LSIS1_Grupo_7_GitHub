<?php
require_once __DIR__ . '/DAL/Admin/DAL_alertas.php';
require_once __DIR__ . '/BLL/Comuns/BLL_notificacoes.php';
require_once __DIR__ . '/DAL/Comuns/Database.php';

$alertasDAL = new DAL_AlertasAdmin();
$notificacoes = new NotificacoesManager();

$alertas = $alertasDAL->getAlertasParaNotificar();

foreach ($alertas as $alerta) {
    // IGNORA a periodicidade para testar
    $destinatario = $alerta['destinatario'];
    $perfil_id = null;
    switch ($destinatario) {
        case 'colaborador': $perfil_id = 2; break;
        case 'coordenador': $perfil_id = 3; break;
        case 'rh': $perfil_id = 4; break;
        case 'admin': $perfil_id = 1; break;
        default: continue 2;
    }
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare("SELECT id FROM utilizadores WHERE perfil_id = ? AND ativo = 1");
    $stmt->execute([$perfil_id]);
    $utilizadores = $stmt->fetchAll();
    foreach ($utilizadores as $u) {
        $notificacoes->enviarNotificacao(null, $u['id'], "[Alerta] {$alerta['tipo']}: {$alerta['descricao']}");
    }
}
echo "Alertas processados e notificações enviadas.\n";
?>
?>
