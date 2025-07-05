<?php
require_once __DIR__ . '/../../DAL/Comuns/DAL_notificacoes.php';

// Check if PHPMailer is available and include it
$phpmailerAvailable = false;
if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
    if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        $phpmailerAvailable = true;
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