<?php
require_once __DIR__ . '/../../DAL/Comuns/DAL_notificacoes.php';

class NotificacoesManager {
    private $dal;
    
    public function __construct() {
        $this->dal = new DAL_Notificacoes();
    }
    
    public function criarNotificacao($utilizador_id, $mensagem, $tipo = null) {
        return $this->dal->criarNotificacao($utilizador_id, $mensagem, $tipo);
    }
    
    public function enviarNotificacao($remetente_id, $destinatario_id, $mensagem, $tipo = null) {
        return $this->dal->criarNotificacao($destinatario_id, $mensagem, $tipo);
    }
    
    public function enviarEmailSimples($email, $assunto, $mensagem) {
        // Debug mais detalhado
        error_log("=== TENTATIVA DE ENVIO DE EMAIL ===");
        error_log("Email de destino: " . $email);
        error_log("Assunto: " . $assunto);
        error_log("Primeira linha da mensagem: " . substr($mensagem, 0, 50) . "...");
        error_log("Função chamada de: " . debug_backtrace()[1]['file'] . ':' . debug_backtrace()[1]['line']);
        
        // Tentar usar mail() do PHP primeiro
        try {
            $headers = "From: noreply@tlantic.com\r\n";
            $headers .= "Reply-To: noreply@tlantic.com\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            
            $sucesso = mail($email, $assunto, $mensagem, $headers);
            
            if ($sucesso) {
                error_log("✅ EMAIL ENVIADO COM SUCESSO usando mail()");
                
                // Salvar uma cópia do email num ficheiro para debug
                $emailContent = "Para: $email\nAssunto: $assunto\nData: " . date('Y-m-d H:i:s') . "\n\n$mensagem\n\n" . str_repeat("=", 50) . "\n\n";
                file_put_contents(__DIR__ . '/../../logs/emails_enviados.log', $emailContent, FILE_APPEND | LOCK_EX);
                
                return true;
            } else {
                error_log("❌ FALHA no envio usando mail()");
            }
        } catch (Exception $e) {
            error_log("❌ ERRO na função mail(): " . $e->getMessage());
        }
        
        // Alternativa: PHPMailer (se disponível)
        if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
            try {
                $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
                
                // Configuração SMTP (exemplo para Gmail)
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'your-email@gmail.com'; // Substituir pelo email real
                $mail->Password = 'your-app-password';    // Substituir pela senha real
                $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                
                $mail->setFrom('noreply@tlantic.com', 'Portal Tlantic');
                $mail->addAddress($email);
                $mail->Subject = $assunto;
                $mail->Body = $mensagem;
                $mail->isHTML(true);
                
                $mail->send();
                error_log("✅ EMAIL ENVIADO COM SUCESSO usando PHPMailer");
                return true;
                
            } catch (Exception $e) {
                error_log("❌ ERRO no PHPMailer: " . $e->getMessage());
            }
        }
        
        // Última alternativa: guardar numa pasta local como ficheiro
        $emailsDir = __DIR__ . '/../../Uploads/emails_pendentes/';
        if (!is_dir($emailsDir)) {
            mkdir($emailsDir, 0777, true);
        }
        
        $filename = 'email_' . time() . '_' . md5($email) . '.html';
        $emailHTML = "
        <html>
        <head><title>$assunto</title></head>
        <body>
            <h2>Email para: $email</h2>
            <h3>Assunto: $assunto</h3>
            <hr>
            $mensagem
            <hr>
            <p><small>Gerado em: " . date('Y-m-d H:i:s') . "</small></p>
        </body>
        </html>";
        
        if (file_put_contents($emailsDir . $filename, $emailHTML)) {
            error_log("📁 EMAIL GUARDADO como ficheiro: " . $emailsDir . $filename);
            error_log("🔗 Abra este ficheiro no browser para ver o email");
            return true;
        }
        
        error_log("❌ TODAS AS TENTATIVAS DE ENVIO FALHARAM");
        return false;
    }
    
    public function getNotificacoesPorUtilizador($utilizador_id) {
        return $this->dal->getNotificacoesPorUtilizador($utilizador_id);
    }
    
    public function marcarComoLida($notificacao_id, $utilizador_id) {
        return $this->dal->marcarComoLida($notificacao_id, $utilizador_id);
    }
    
    public function contarNaoLidas($utilizador_id) {
        return $this->dal->contarNaoLidas($utilizador_id);
    }
    
    public function notificarRH($mensagem, $tipo = null) {
        // Buscar todos os utilizadores RH ativos
        $utilizadoresRH = $this->dal->getUtilizadoresRH();
        $sucessos = 0;
        
        foreach ($utilizadoresRH as $rh) {
            if ($this->criarNotificacao($rh['id'], $mensagem, $tipo)) {
                $sucessos++;
            }
        }
        
        return $sucessos;
    }
    
    public function notificarTodos($mensagem, $tipo = null) {
        // Buscar todos os utilizadores ativos
        $utilizadores = $this->dal->getTodosUtilizadores();
        $sucessos = 0;
        
        foreach ($utilizadores as $user) {
            if ($this->criarNotificacao($user['id'], $mensagem, $tipo)) {
                $sucessos++;
            }
        }
        
        return $sucessos;
    }
    
    public function notificarPorPerfil($perfil, $mensagem, $tipo = null) {
        // Buscar utilizadores por perfil
        $utilizadores = $this->dal->getUtilizadoresPorPerfil($perfil);
        $sucessos = 0;
        
        foreach ($utilizadores as $user) {
            if ($this->criarNotificacao($user['id'], $mensagem, $tipo)) {
                $sucessos++;
            }
        }
        
        return $sucessos;
    }
    
    public function marcarTodasComoLidas($utilizador_id) {
        return $this->dal->marcarTodasComoLidas($utilizador_id);
    }
}
?>