<?php
require_once __DIR__ . '/../../DAL/Comuns/DAL_notificacoes.php';
require_once __DIR__ . '/../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

<<<<<<< Updated upstream
// Check if PHPMailer is available and include it
$phpmailerAvailable = false;
if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
    if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        $phpmailerAvailable = true;
=======
class NotificacoesManager {
    private $dal;
    
    public function __construct() {
        $this->dal = new DAL_Notificacoes();
    }

    // Gera o assunto do email de notificação
    private function getAssuntoPadrao($mensagem, $tipo = null) {
        $prefixo = '[Portal Tlantic] Nova Notificação';
        $tipoStr = $tipo ? ucfirst(str_replace('_', ' ', $tipo)) : 'Geral';
        // Resumo: primeiras 8 palavras da mensagem
        $resumo = implode(' ', array_slice(explode(' ', strip_tags($mensagem)), 0, 8));
        return "$prefixo: $tipoStr — $resumo" . (strlen($mensagem) > strlen($resumo) ? '...' : '');
    }

    // Gera o corpo HTML do email de notificação (estético e informativo)
    private function getCorpoPadrao($mensagem, $tipo = null, $destinatarioNome = null) {
        // Ícones por tipo
        $icones = [
            'voucher_expirado' => '🎫',
            'alteracao_fiscal' => '📋',
            'ferias' => '🏖️',
            'comprovativo' => '📎',
            'onboarding' => '📝',
            'alerta' => '🚨',
            'geral' => '🔔'
        ];
        $tipoKey = $tipo ? strtolower($tipo) : 'geral';
        $icone = $icones[$tipoKey] ?? $icones['geral'];
        $tipoStr = $tipo ? ucfirst(str_replace('_', ' ', $tipo)) : 'Notificação';

        $saudacao = $destinatarioNome ? "Olá, <b>$destinatarioNome</b>!" : "Olá!";
        $dataEnvio = date('d/m/Y H:i');

        return '
        <html>
        <head>
        <meta charset="UTF-8">
        <title>Nova Notificação - Portal Tlantic</title>
        <style>
            body { background: #f7faff; margin: 0; padding: 0; font-family: "Segoe UI", Arial, sans-serif; }
            .container { max-width: 480px; margin: 32px auto; background: #fff; border-radius: 18px; box-shadow: 0 8px 32px #0360e91a, 0 2px 8px #0001; padding: 0 0 32px 0; overflow: hidden; }
            .header { background: linear-gradient(135deg, #0360e9 0%, #299cf3 100%); color: #fff; padding: 32px 24px 18px 24px; text-align: center; border-radius: 18px 18px 0 0; }
            .header .icon { font-size: 2.7rem; margin-bottom: 8px; display: block; }
            .header .title { font-size: 1.25rem; font-weight: 700; margin: 0 0 4px 0; letter-spacing: 0.5px; }
            .header .subtitle { font-size: 1.01rem; opacity: 0.93; margin-bottom: 0; }
            .content { padding: 32px 28px 0 28px; }
            .saudacao { font-size: 1.08rem; color: #23408e; margin-bottom: 18px; }
            .mensagem { background: #f7faff; border-left: 4px solid #0360e9; border-radius: 10px; padding: 18px 18px 18px 18px; font-size: 1.08rem; color: #23408e; margin-bottom: 18px; box-shadow: 0 2px 8px #0360e91a; }
            .info { color: #888; font-size: 0.97rem; margin-bottom: 18px; }
            .btn-aceder { display: inline-block; background: linear-gradient(135deg, #0360e9 0%, #299cf3 100%); color: #fff; font-weight: 700; padding: 14px 32px; border-radius: 10px; text-decoration: none; font-size: 1.08rem; margin: 0 auto 18px auto; box-shadow: 0 4px 16px #0360e91a; letter-spacing: 0.2px; transition: background 0.2s; }
            .btn-aceder:hover { background: linear-gradient(135deg, #299cf3 0%, #0360e9 100%); }
            .footer { text-align: center; color: #aaa; font-size: 0.93rem; margin-top: 32px; }
            .footer img { height: 22px; opacity: 0.7; margin-top: 8px; }
        </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <span class="icon">'.$icone.'</span>
                    <div class="title">'.$tipoStr.'</div>
                    <div class="subtitle">Nova notificação do Portal Tlantic</div>
                </div>
                <div class="content">
                    <div class="saudacao">'.$saudacao.'</div>
                    <div class="mensagem">'.nl2br(htmlspecialchars($mensagem)).'</div>
                    <div class="info">Recebida em: <b>'.$dataEnvio.'</b></div>
                    <div style="text-align:center;">
                        <a href="http://localhost/LSIS1_Grupo_7_GitHub/UI/Comuns/login.php" class="btn-aceder">Aceder ao Portal Tlantic</a>
                    </div>
                </div>
                <div class="footer">
                    Este email foi enviado automaticamente pelo Portal Tlantic.<br>
                    Por favor, não responda a este email.<br>
                    <img src="https://www.tlantic.com/wp-content/uploads/2021/03/logo-tlantic.png" alt="Tlantic">
                </div>
            </div>
        </body>
        </html>
        ';
    }

    // Envia email de notificação para um utilizador
    private function enviarEmailNotificacao($utilizador_id, $mensagem, $tipo = null) {
        $user = $this->dal->getUtilizadorById($utilizador_id);
        if (!$user || empty($user['email'])) return false;
        $assunto = $this->getAssuntoPadrao($mensagem, $tipo);
        $corpo = $this->getCorpoPadrao($mensagem, $tipo, $user['username']);
        return $this->enviarEmailSimples($user['email'], $assunto, $corpo);
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
    
    public function enviarEmail($email, $assunto, $mensagem) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'suportetlantic@gmail.com';
            $mail->Password   = 'sdmghevbfzqglaca';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('suportetlantic@gmail.com', 'Portal Tlantic');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = $assunto;
            $mail->Body    = $mensagem;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("❌ Erro ao enviar email: " . $e->getMessage());
            return false;
        }
    }

    public function enviarEmailSimples($email, $assunto, $mensagem) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'suportetlantic@gmail.com';
            $mail->Password   = 'sdmghevbfzqglaca';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('suportetlantic@gmail.com', 'Portal Tlantic');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = $assunto;
            $mail->Body    = $mensagem;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("❌ Erro ao enviar email: " . $e->getMessage());
            return false;
        }
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
>>>>>>> Stashed changes
    }
}

class NotificacoesManager {
    private $dal;
    private $phpmailerAvailable;
    
    public function __construct() {
        global $phpmailerAvailable;
        $this->dal = new DAL_Notificacoes();
        $this->phpmailerAvailable = $phpmailerAvailable;
    }

    public function enviarNotificacao($remetenteId, $destinatarioId, $mensagem) {
        // Sempre salva no banco
        $this->dal->enviarNotificacao($remetenteId, $destinatarioId, $mensagem);

        // Envia email também
        if (!$this->phpmailerAvailable) {
            return true;
        }

        // Buscar remetente (nome do utilizador, pode ser da tabela utilizadores)
        $remetente = $remetenteId ? $this->dal->getUtilizadorById($remetenteId) : ['username' => 'Sistema'];

        // Buscar destinatário: email da tabela colaboradores
        $colaborador = $this->dal->getColaboradorByUtilizadorId($destinatarioId);
        if (!$colaborador || empty($colaborador['email'])) return true;

        $dataHora = date('d/m/Y H:i');
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'suportetlantic@gmail.com';
            $mail->Password   = 'xznvhgxwnicdcrpx';
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('suportetlantic@gmail.com', 'Portal Tlantic');
            $mail->addAddress($colaborador['email'], $colaborador['nome'] ?? '');
            $mail->isHTML(true);
            $mail->Subject = 'Nova Notificação no Portal Tlantic';

            // Corrigido: usa o logo real do projeto
            $logoUrl = 'http://localhost/LSIS1_Grupo_7_GitHub/assets/tlantic-logo2.png';
            $mail->Body = '
            <html>
            <head><meta charset="UTF-8"></head>
            <body style="background:#f7f7fa;padding:32px 0;">
                <div style="max-width:420px;margin:0 auto;background:#fff;border-radius:12px;box-shadow:0 2px 16px rgba(102,126,234,0.10);padding:32px 28px 28px 28px;font-family:\'Segoe UI\',Arial,sans-serif;">
                    <div style="text-align:center;margin-bottom:18px;">
                        <img src="' . $logoUrl . '" alt="Tlantic" style="height:48px;">
                    </div>
                    <h2 style="color:#667eea;text-align:center;margin-bottom:18px;font-weight:700;letter-spacing:0.5px;font-size:1.3rem;">Nova Notificação Recebida</h2>
                    <p style="color:#333;text-align:center;font-size:1.05rem;margin-bottom:18px;">
                        Olá <b>' . htmlspecialchars($colaborador['nome'] ?? '') . '</b>,<br>
                        Recebeu uma nova notificação no <b>Portal Tlantic</b>.
                    </p>
                    <div style="background:#f5f7fa;border-left:5px solid #667eea;padding:18px 18px 12px 18px;border-radius:8px;margin-bottom:18px;">
                        <div style="color:#764ba2;font-size:0.98rem;margin-bottom:6px;">
                            <b>De:</b> ' . htmlspecialchars($remetente['username'] ?? 'Sistema') . '<br>
                            <b>Data/Hora:</b> ' . $dataHora . '
                        </div>
                        <div style="color:#23408e;font-size:1.08rem;margin-top:8px;">
                            <b>Mensagem:</b><br>
                            <span style="color:#333;">' . nl2br(htmlspecialchars($mensagem)) . '</span>
                        </div>
                    </div>
                    <p style="color:#444;text-align:center;font-size:0.98rem;margin-bottom:18px;">
                        Para mais detalhes, aceda ao Portal Tlantic.<br>
                        <a href="http://localhost/LSIS1_Grupo_7_GitHub/" style="color:#667eea;text-decoration:none;font-weight:600;">Aceder ao Portal</a>
                    </p>
                    <div style="margin-top:32px;text-align:center;color:#aaa;font-size:0.90rem;">
                        &copy; ' . date('Y') . ' Tlantic. Todos os direitos reservados.
                    </div>
                </div>
            </body>
            </html>
            ';

            $mail->AltBody = "Nova notificação no Portal Tlantic\nDe: " . ($remetente['username'] ?? 'Sistema') . "\nData/Hora: $dataHora\nMensagem: $mensagem\nAcesse o portal para mais informações.";

            $mail->send();
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            error_log("Email sending failed: " . $e->getMessage());
        }
        
        return true;
    }

    public function getNotificacoesByUserId($userId) {
        return $this->dal->getNotificacoesByUserId($userId);
    }
    public function marcarComoLida($notificacaoId) {
        return $this->dal->marcarComoLida($notificacaoId);
    }
    public function notificarRH($mensagem) {
        // Buscar todos os RH
        $rhs = $this->dal->getRHUsers();
        foreach ($rhs as $rh) {
            $this->enviarNotificacao(null, $rh['id'], $mensagem);
        }
        return true;
    }
    public function eliminarNotificacao($id) {
        return $this->dal->eliminarNotificacao($id);
    }
    public function notificarColaboradorPedidoAlteracao($colaboradorId, $status, $campo, $valorAntigo, $valorNovo) {
        // Convert colaborador_id to utilizador_id
        $utilizadorId = $this->dal->getUtilizadorIdByColaboradorId($colaboradorId);
        if (!$utilizadorId) {
            return false; // Invalid colaborador_id
        }

        // First send internal notification
        $statusTxt = $status === 'aprovado' ? 'aprovado' : 'recusado';
        $msg = "O seu pedido de alteração do campo '$campo' foi $statusTxt pelo RH.";
        $this->dal->enviarNotificacao(null, $utilizadorId, $msg);

        // Email: buscar email do colaborador
        if (!$this->phpmailerAvailable) {
            return true;
        }
        $colaborador = $this->dal->getColaboradorByUtilizadorId($utilizadorId);
        if (!$colaborador || empty($colaborador['email'])) return true;

        $statusTxtEmail = $status === 'aprovado' ? 'Aprovado' : 'Recusado';
        $corStatus = $status === 'aprovado' ? '#38a169' : '#e53e3e';

        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'suportetlantic@gmail.com';
            $mail->Password   = 'xznvhgxwnicdcrpx';
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('suportetlantic@gmail.com', 'Portal Tlantic');
            $mail->addAddress($colaborador['email'], $colaborador['nome'] ?? '');
            $mail->isHTML(true);
            $mail->Subject = 'Status do Pedido de Alteração - Portal Tlantic';

            $mail->Body = '
            <html>
            <head><meta charset="UTF-8"></head>
            <body style="background:#f7f7fa;padding:32px 0;">
                <div style="max-width:420px;margin:0 auto;background:#fff;border-radius:12px;box-shadow:0 2px 16px rgba(102,126,234,0.10);padding:32px 28px 28px 28px;font-family:\'Segoe UI\',Arial,sans-serif;">
                    <div style="text-align:center;margin-bottom:18px;">
                        <img src="https://i.imgur.com/8oQw7Qz.png" alt="Tlantic" style="height:48px;">
                    </div>
                    <h2 style="color:#667eea;text-align:center;margin-bottom:18px;font-weight:700;letter-spacing:0.5px;font-size:1.2rem;">Pedido de Alteração ' . $statusTxtEmail . '</h2>
                    <p style="color:#333;text-align:center;font-size:1.05rem;margin-bottom:18px;">
                        Olá <b>' . htmlspecialchars($colaborador['nome'] ?? '') . '</b>,<br>
                        O seu pedido de alteração foi <span style="color:' . $corStatus . ';font-weight:600;">' . $statusTxtEmail . '</span> pelo RH.
                    </p>
                    <div style="background:#f5f7fa;border-left:5px solid #667eea;padding:16px 18px 12px 18px;border-radius:8px;margin-bottom:18px;">
                        <div style="color:#23408e;font-size:1.05rem;">
                            <b>Campo:</b> ' . htmlspecialchars($campo) . '<br>
                            <b>De:</b> ' . htmlspecialchars($valorAntigo) . '<br>
                            <b>Para:</b> <span style="color:#764ba2;">' . htmlspecialchars($valorNovo) . '</span>
                        </div>
                    </div>
                    <p style="color:#444;text-align:center;font-size:0.98rem;margin-bottom:18px;">
                        Para mais detalhes, aceda ao Portal Tlantic.<br>
                        <a href="http://localhost/LSIS1_Grupo_7_GitHub/" style="color:#667eea;text-decoration:none;font-weight:600;">Aceder ao Portal</a>
                    </p>
                    <div style="margin-top:32px;text-align:center;color:#aaa;font-size:0.90rem;">
                        &copy; ' . date('Y') . ' Tlantic. Todos os direitos reservados.
                    </div>
                </div>
            </body>
            </html>
            ';

            $mail->AltBody = "O seu pedido de alteração foi $statusTxtEmail pelo RH.\nCampo: $campo\nDe: $valorAntigo\nPara: $valorNovo\nAcesse o portal para mais informações.";

            $mail->send();
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            error_log("Email sending failed: " . $e->getMessage());
        }
        
        return true;
    }

    public function notificarColaboradorPedidoFerias($colaboradorId, $status, $dataInicio, $dataFim) {
        // Convert colaborador_id to utilizador_id
        $utilizadorId = $this->dal->getUtilizadorIdByColaboradorId($colaboradorId);
        if (!$utilizadorId) {
            return false; // Invalid colaborador_id
        }

        // First send internal notification
        $statusTxt = $status === 'aceite' ? 'aprovado' : 'recusado';
        $msg = "O seu pedido de férias de $dataInicio até $dataFim foi $statusTxt pelo RH.";
        $this->dal->enviarNotificacao(null, $utilizadorId, $msg);

        // Email: buscar email do colaborador
        if (!$this->phpmailerAvailable) {
            return true;
        }
        $colaborador = $this->dal->getColaboradorByUtilizadorId($utilizadorId);
        if (!$colaborador || empty($colaborador['email'])) return true;

        $statusTxtEmail = $status === 'aceite' ? 'Aprovado' : 'Recusado';
        $corStatus = $status === 'aceite' ? '#38a169' : '#e53e3e';

        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'suportetlantic@gmail.com';
            $mail->Password   = 'xznvhgxwnicdcrpx';
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('suportetlantic@gmail.com', 'Portal Tlantic');
            $mail->addAddress($colaborador['email'], $colaborador['nome'] ?? '');
            $mail->isHTML(true);
            $mail->Subject = 'Status do Pedido de Férias - Portal Tlantic';

            $mail->Body = '
            <html>
            <head><meta charset="UTF-8"></head>
            <body style="background:#f7f7fa;padding:32px 0;">
                <div style="max-width:420px;margin:0 auto;background:#fff;border-radius:12px;box-shadow:0 2px 16px rgba(102,126,234,0.10);padding:32px 28px 28px 28px;font-family:\'Segoe UI\',Arial,sans-serif;">
                    <div style="text-align:center;margin-bottom:18px;">
                        <img src="https://i.imgur.com/8oQw7Qz.png" alt="Tlantic" style="height:48px;">
                    </div>
                    <h2 style="color:#667eea;text-align:center;margin-bottom:18px;font-weight:700;letter-spacing:0.5px;font-size:1.2rem;">Pedido de Férias ' . $statusTxtEmail . '</h2>
                    <p style="color:#333;text-align:center;font-size:1.05rem;margin-bottom:18px;">
                        Olá <b>' . htmlspecialchars($colaborador['nome'] ?? '') . '</b>,<br>
                        O seu pedido de férias foi <span style="color:' . $corStatus . ';font-weight:600;">' . $statusTxtEmail . '</span> pelo RH.<br>
                        <b>De:</b> ' . htmlspecialchars($dataInicio) . ' <b>até</b> ' . htmlspecialchars($dataFim) . '
                    </p>
                    <p style="color:#444;text-align:center;font-size:0.98rem;margin-bottom:18px;">
                        Para mais detalhes, aceda ao Portal Tlantic.<br>
                        <a href="http://localhost/LSIS1_Grupo_7_GitHub/" style="color:#667eea;text-decoration:none;font-weight:600;">Aceder ao Portal</a>
                    </p>
                    <div style="margin-top:32px;text-align:center;color:#aaa;font-size:0.90rem;">
                        &copy; ' . date('Y') . ' Tlantic. Todos os direitos reservados.
                    </div>
                </div>
            </body>
            </html>
            ';
            $mail->AltBody = "O seu pedido de férias foi $statusTxtEmail pelo RH.\nDe: $dataInicio até $dataFim\nAcesse o portal para mais informações.";
            $mail->send();
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            error_log("Email sending failed: " . $e->getMessage());
        }
        
        return true;
    }

    public function notificarColaboradorComprovativo($colaboradorId, $status, $tipoComprovativo) {
        // Convert colaborador_id to utilizador_id
        $utilizadorId = $this->dal->getUtilizadorIdByColaboradorId($colaboradorId);
        if (!$utilizadorId) {
            return false;
        }

        $statusTxt = $status === 'aprovado' ? 'aprovado' : 'recusado';
        $tipoTexto = ucfirst(str_replace('_', ' ', $tipoComprovativo));
        $msg = "O seu comprovativo '$tipoTexto' foi $statusTxt pelo RH.";
        $this->dal->enviarNotificacao(null, $utilizadorId, $msg);

        // Email: buscar email do colaborador
        if (!$this->phpmailerAvailable) {
            return true;
        }
        $colaborador = $this->dal->getColaboradorByUtilizadorId($utilizadorId);
        if (!$colaborador || empty($colaborador['email'])) return true;

        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'suportetlantic@gmail.com';
            $mail->Password   = 'xznvhgxwnicdcrpx';
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('suportetlantic@gmail.com', 'Portal Tlantic');
            $mail->addAddress($colaborador['email'], $colaborador['nome'] ?? '');
            $mail->isHTML(true);
            $mail->Subject = 'Status do Comprovativo - Portal Tlantic';

            $mail->Body = '
            <html>
            <head><meta charset="UTF-8"></head>
            <body style="background:#f7f7fa;padding:32px 0;">
                <div style="max-width:420px;margin:0 auto;background:#fff;border-radius:12px;box-shadow:0 2px 16px rgba(102,126,234,0.10);padding:32px 28px 28px 28px;font-family:\'Segoe UI\',Arial,sans-serif;">
                    <div style="text-align:center;margin-bottom:18px;">
                        <img src="https://i.imgur.com/8oQw7Qz.png" alt="Tlantic" style="height:48px;">
                    </div>
                    <h2 style="color:#667eea;text-align:center;margin-bottom:18px;font-weight:700;letter-spacing:0.5px;font-size:1.2rem;">Status do Comprovativo</h2>
                    <p style="color:#333;text-align:center;font-size:1.05rem;margin-bottom:18px;">
                        Olá <b>' . htmlspecialchars($colaborador['nome'] ?? '') . '</b>,<br>
                        O seu comprovativo <b>' . htmlspecialchars($tipoTexto) . '</b> foi <b>' . $statusTxt . '</b> pelo RH.
                    </p>
                    <p style="color:#444;text-align:center;font-size:0.98rem;margin-bottom:18px;">
                        Para mais detalhes, aceda ao Portal Tlantic.<br>
                        <a href="http://localhost/LSIS1_Grupo_7_GitHub/" style="color:#667eea;text-decoration:none;font-weight:600;">Aceder ao Portal</a>
                    </p>
                    <div style="margin-top:32px;text-align:center;color:#aaa;font-size:0.90rem;">
                        &copy; ' . date('Y') . ' Tlantic. Todos os direitos reservados.
                    </div>
                </div>
            </body>
            </html>
            ';
            $mail->AltBody = "O seu comprovativo $tipoTexto foi $statusTxt pelo RH.\nAcesse o portal para mais informações.";
            $mail->send();
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            error_log("Email sending failed: " . $e->getMessage());
        }
        
        return true;
    }

    public function enviarEmailSimples($to, $subject, $body) {
        if (!$this->phpmailerAvailable) return true;
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'suportetlantic@gmail.com';
            $mail->Password   = 'xznvhgxwnicdcrpx';
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->setFrom('suportetlantic@gmail.com', 'Portal Tlantic');
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = strip_tags($body);
            $mail->send();
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            error_log("Email sending failed: " . $e->getMessage());
        }
        return true;
    }
}