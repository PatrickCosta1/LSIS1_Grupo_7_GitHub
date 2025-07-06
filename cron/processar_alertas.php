<?php
require_once __DIR__ . '/../BLL/Admin/BLL_alertas.php';
require_once __DIR__ . '/../BLL/Comuns/BLL_notificacoes.php';
require_once __DIR__ . '/../BLL/Comuns/BLL_email.php';
require_once __DIR__ . '/../DAL/Database.php';

// Processar alertas de voucher NOS
function processarAlertasVoucherNOS() {
    $pdo = Database::getConnection();
    $notBLL = new NotificacoesManager();
    $emailBLL = new EmailManager();
    
    // Buscar colaboradores com voucher_nos expirado (mais de 23 meses)
    $stmt = $pdo->prepare("
        SELECT c.id, c.nome, c.voucher_nos, c.utilizador_id, u.email
        FROM colaboradores c
        INNER JOIN utilizadores u ON c.utilizador_id = u.id
        WHERE c.voucher_nos IS NOT NULL 
        AND c.voucher_nos != '0000-00-00'
        AND c.voucher_nos < DATE_SUB(CURDATE(), INTERVAL 23 MONTH)
    ");
    $stmt->execute();
    $colaboradores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($colaboradores as $colab) {
        $diasExpirado = floor((time() - strtotime($colab['voucher_nos'])) / (60 * 60 * 24));
        $mesesExpirado = floor($diasExpirado / 30);
        
        $mensagem = "O voucher NOS do colaborador {$colab['nome']} expirou há {$mesesExpirado} meses (última emissão: " . date('d/m/Y', strtotime($colab['voucher_nos'])) . "). É necessário renovar.";
        
        // Notificar RH (portal)
        $notBLL->notificarRH($mensagem);
        
        // Notificar RH (email)
        $emailBLL->enviarEmailRH("Alerta: Voucher NOS Expirado - {$colab['nome']}", $mensagem);
        
        // Notificar o próprio colaborador (portal) - ALERTA MAIS VISÍVEL
        $mensagemColab = "🚨 ALERTA URGENTE: O seu voucher NOS expirou há {$mesesExpirado} meses (última emissão: " . date('d/m/Y', strtotime($colab['voucher_nos'])) . "). Contacte IMEDIATAMENTE os Recursos Humanos para renovação. Esta situação pode afetar os seus benefícios.";
        $notBLL->criarNotificacao($colab['utilizador_id'], $mensagemColab, 'voucher_expirado');

        // Notificar o próprio colaborador (email) - EMAIL MAIS URGENTE
        if (!empty($colab['email'])) {
            $emailBLL->enviarEmail(
                $colab['email'],
                "🚨 URGENTE: Voucher NOS Expirado - Ação Necessária",
                "Caro(a) {$colab['nome']},<br><br><strong>O seu voucher NOS expirou há {$mesesExpirado} meses.</strong><br><br>📅 Última emissão: " . date('d/m/Y', strtotime($colab['voucher_nos'])) . "<br><br>⚠️ <strong>É necessário contactar os Recursos Humanos URGENTEMENTE para renovação.</strong><br><br>Esta situação pode afetar os seus benefícios corporativos e requer atenção imediata."
            );
        }
        
        error_log("Alerta voucher NOS enviado para: " . $colab['nome'] . " (Email: " . $colab['email'] . ")");
    }
    
    return count($colaboradores);
}

// Executar o processamento
if (php_sapi_name() === 'cli' || ($_GET['exec'] ?? '') === 'true') {
    $count = processarAlertasVoucherNOS();
    echo "Processados {$count} alertas de voucher NOS (portal + email).\n";
}
?>
