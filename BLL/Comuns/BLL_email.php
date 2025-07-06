<?php

class EmailManager {
    private $smtpHost = 'smtp.gmail.com';
    private $smtpPort = 587;
    private $smtpUser = 'portal@tlantic.com';
    private $smtpPass = 'senha_portal';
    
    public function enviarEmail($destinatario, $assunto, $mensagem) {
        // Simulação de envio de email
        // Em produção, usar PHPMailer ou similar
        
        $headers = [
            'From: Portal Tlantic <portal@tlantic.com>',
            'Reply-To: portal@tlantic.com',
            'Content-Type: text/html; charset=UTF-8',
            'X-Mailer: Portal Tlantic'
        ];
        
        $corpo = $this->gerarTemplateEmail($mensagem, $assunto);
        
        // Log do email enviado (mais detalhado)
        $logDir = __DIR__ . '/../../logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
        $logEntry = date('Y-m-d H:i:s') . " - Email enviado para: {$destinatario} - Assunto: {$assunto}\n";
        error_log($logEntry, 3, $logDir . '/emails.log');
        
        // Também fazer log no error_log padrão do PHP para debug
        error_log("EMAIL SIMULADO: Para {$destinatario}, Assunto: {$assunto}");
        
        // Em desenvolvimento, apenas simular o envio
        if ($_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) {
            return true; // Simular sucesso em ambiente local
        }
        
        // Em produção, usar mail() ou PHPMailer
        return mail($destinatario, $assunto, $corpo, implode("\r\n", $headers));
    }
    
    public function enviarEmailRH($assunto, $mensagem) {
        require_once __DIR__ . '/../../DAL/Database.php';
        $pdo = Database::getConnection();
        
        // Buscar todos os emails dos utilizadores RH
        $stmt = $pdo->prepare("
            SELECT u.email 
            FROM utilizadores u 
            INNER JOIN perfis p ON u.perfil_id = p.id 
            WHERE p.nome = 'rh' AND u.ativo = 1
        ");
        $stmt->execute();
        $emailsRH = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $sucessos = 0;
        foreach ($emailsRH as $email) {
            if ($this->enviarEmail($email, $assunto, $mensagem)) {
                $sucessos++;
            }
        }
        
        return $sucessos;
    }
    
    private function gerarTemplateEmail($mensagem, $assunto) {
        // Template especial para alertas de voucher
        $isVoucherAlert = strpos($assunto, 'Voucher') !== false || strpos($assunto, 'URGENTE') !== false;
        $headerColor = $isVoucherAlert ? '#ff6b35' : '#0360e9';
        $headerIcon = $isVoucherAlert ? '🚨' : '📧';
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>{$assunto}</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
                .container { max-width: 600px; margin: 0 auto; }
                .header { background: {$headerColor}; color: white; padding: 20px; text-align: center; }
                .content { background: #f9f9f9; padding: 20px; border-left: 4px solid {$headerColor}; }
                .footer { background: #333; color: white; padding: 10px; text-align: center; font-size: 12px; }
                .urgent { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 8px; margin: 10px 0; }
                .btn { background: {$headerColor}; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block; margin: 10px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>{$headerIcon} Portal Tlantic</h1>
                    <h2>{$assunto}</h2>
                </div>
                <div class='content'>
                    " . ($isVoucherAlert ? "<div class='urgent'><strong>⚠️ AÇÃO NECESSÁRIA</strong></div>" : "") . "
                    <p>{$mensagem}</p>
                    <p>Para mais informações, aceda ao <a href='http://localhost/LSIS/LSIS1_Grupo_7_GitHub' class='btn'>Portal Tlantic</a></p>
                </div>
                <div class='footer'>
                    <p>Este email foi enviado automaticamente pelo Sistema de Alertas do Portal Tlantic.</p>
                    <p>Por favor, não responda a este email.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
?>
