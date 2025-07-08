<?php
require_once __DIR__ . '/../../DAL/Comuns/DAL_notificacoes.php';
// Adicionar autoload do Composer para PHPMailer
require_once __DIR__ . '/../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class NotificacoesManager {
    private $dal;
    
    public function __construct() {
        $this->dal = new DAL_Notificacoes();
    }
    
    public function criarNotificacao($utilizador_id, $mensagem, $tipo = null) {
        $ok = $this->dal->criarNotificacao($utilizador_id, $mensagem, $tipo);
        $this->enviarEmailNotificacao($utilizador_id, $mensagem, $tipo);
        return $ok;
    }
    
    public function enviarNotificacao($remetente_id, $destinatario_id, $mensagem, $tipo = null) {
        $ok = $this->dal->criarNotificacao($destinatario_id, $mensagem, $tipo);
        $this->enviarEmailNotificacao($destinatario_id, $mensagem, $tipo);
        return $ok;
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
    
    public function marcarTodasComoLidas($utilizador_id) {
        return $this->dal->marcarTodasComoLidas($utilizador_id);
    }
    
    public function notificarRH($mensagem, $tipo = null) {
        $utilizadoresRH = $this->dal->getUtilizadoresRH();
        $sucessos = 0;
        foreach ($utilizadoresRH as $rh) {
            if ($this->criarNotificacao($rh['id'], $mensagem, $tipo)) {
                $sucessos++;
            }
            // Email também enviado dentro de criarNotificacao
        }
        return $sucessos;
    }
    
    public function notificarTodos($mensagem, $tipo = null) {
        $utilizadores = $this->dal->getTodosUtilizadores();
        $sucessos = 0;
        foreach ($utilizadores as $user) {
            if ($this->criarNotificacao($user['id'], $mensagem, $tipo)) {
                $sucessos++;
            }
            // Email também enviado dentro de criarNotificacao
        }
        return $sucessos;
    }
    
    public function notificarPorPerfil($perfil, $mensagem, $tipo = null) {
        $utilizadores = $this->dal->getUtilizadoresPorPerfil($perfil);
        $sucessos = 0;
        foreach ($utilizadores as $user) {
            if ($this->criarNotificacao($user['id'], $mensagem, $tipo)) {
                $sucessos++;
            }
            // Email também enviado dentro de criarNotificacao
        }
        return $sucessos;
    }

    // Função utilitária para envio de email estilizado de notificação
    private function enviarEmailNotificacao($utilizador_id, $mensagem, $tipo = null) {
        // Buscar email do utilizador
        $email = $this->dal->getEmailByUtilizadorId($utilizador_id);
        if (!$email) return;

        $assunto = "Notificação - Portal Tlantic";
        $body = $this->templateEmailNotificacao($mensagem);

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'suportetlantic@gmail.com';
            $mail->Password   = 'qfas jxch tmub iboy';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port= 587;

            $mail->setFrom('suportetlantic@gmail.com', 'Portal Tlantic');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = $assunto;
            $mail->Body = $body;
            $mail->AltBody = strip_tags($mensagem);

            $mail->send();
        } catch (Exception $e) {
            // Logar erro se necessário, mas não impedir criação da notificação
        }
    }

    // Template HTML para email de notificação
    private function templateEmailNotificacao($mensagem) {
        $ano = date('Y');
        return '
        <div style="background:#f7f7fa;padding:32px 0;">
            <div style="max-width:420px;margin:0 auto;background:#fff;border-radius:12px;box-shadow:0 2px 16px rgba(102,126,234,0.10);padding:32px 28px 28px 28px;font-family:\'Segoe UI\',Arial,sans-serif;">
                <div style="text-align:center;margin-bottom:18px;">
                    <img src="https://i.imgur.com/8oQw7Qz.png" alt="Tlantic" style="height:48px;">
                </div>
                <h2 style="color:#667eea;text-align:center;margin-bottom:18px;font-weight:700;letter-spacing:0.5px;font-size:1.2rem;">Notificação do Portal Tlantic</h2>
                <div style="color:#333;text-align:center;font-size:1.05rem;margin-bottom:24px;">
                    ' . $mensagem . '
                </div>
                <div style="margin-top:32px;text-align:center;color:#aaa;font-size:0.90rem;">
                    &copy; ' . $ano . ' Tlantic. Todos os direitos reservados.
                </div>
            </div>
        </div>
        ';
    }

    // Método utilitário para envio de emails simples (já existente)
    public function enviarEmailSimples($email, $assunto, $mensagem) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'suportetlantic@gmail.com';
            $mail->Password   = 'qfas jxch tmub iboy';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port= 587;

            $mail->setFrom('suportetlantic@gmail.com', 'Portal Tlantic');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = $assunto;
            $mail->Body = $mensagem;
            $mail->AltBody = strip_tags($mensagem);

            $mail->send();
        } catch (Exception $e) {
            // Logar erro se necessário
        }
    }
}
?>