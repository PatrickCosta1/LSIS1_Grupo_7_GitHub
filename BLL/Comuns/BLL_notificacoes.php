<?php
require_once __DIR__ . '/../../DAL/Comuns/DAL_notificacoes.php';
require_once __DIR__ . '/../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class NotificacoesManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_Notificacoes();
    }

    public function enviarNotificacao($remetenteId, $destinatarioId, $mensagem) {
        $this->dal->enviarNotificacao($remetenteId, $destinatarioId, $mensagem);

        $remetente = $remetenteId ? $this->dal->getUtilizadorById($remetenteId) : ['username' => 'Sistema'];
        $destinatario = $this->dal->getUtilizadorById($destinatarioId);

        if (!$destinatario || empty($destinatario['email'])) return;

        $dataHora = date('d/m/Y H:i');
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'patrickcosta1605@gmail.com';
            $mail->Password   = '';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('patrickcosta1605@gmail.com', 'Portal Tlantic');
            $mail->addAddress($destinatario['email'], $destinatario['username'] ?? '');
            $mail->isHTML(true);
            $mail->Subject = 'Nova Notificação no Portal Tlantic';

            $mail->Body = '
            <div style="background:#f7f7fa;padding:32px 0;">
                <div style="max-width:420px;margin:0 auto;background:#fff;border-radius:12px;box-shadow:0 2px 16px rgba(102,126,234,0.10);padding:32px 28px 28px 28px;font-family:\'Segoe UI\',Arial,sans-serif;">
                    <div style="text-align:center;margin-bottom:18px;">
                        <img src="https://i.imgur.com/8oQw7Qz.png" alt="Tlantic" style="height:48px;">
                    </div>
                    <h2 style="color:#667eea;text-align:center;margin-bottom:18px;font-weight:700;letter-spacing:0.5px;font-size:1.3rem;">Nova Notificação Recebida</h2>
                    <p style="color:#333;text-align:center;font-size:1.05rem;margin-bottom:18px;">
                        Olá <b>' . htmlspecialchars($destinatario['username'] ?? '') . '</b>,<br>
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
            </div>
            ';

            $mail->AltBody = "Nova notificação no Portal Tlantic\nDe: " . ($remetente['username'] ?? 'Sistema') . "\nData/Hora: $dataHora\nMensagem: $mensagem\nAcesse o portal para mais informações.";

            $mail->send();
        } catch (Exception $e) {
            // Logar erro se necessário
        }
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
        $colaborador = $this->dal->getUtilizadorById($colaboradorId);
        if (!$colaborador || empty($colaborador['email'])) return;

        $statusTxt = $status === 'aprovado' ? 'Aprovado' : 'Recusado';
        $corStatus = $status === 'aprovado' ? '#38a169' : '#e53e3e';

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'patrickcosta1605@gmail.com';
            $mail->Password   = 'sxcxatuvhpcwgohu';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('patrickcosta1605@gmail.com', 'Portal Tlantic');
            $mail->addAddress($colaborador['email'], $colaborador['username'] ?? '');
            $mail->isHTML(true);
            $mail->Subject = 'Status do Pedido de Alteração - Portal Tlantic';

            $mail->Body = '
            <div style="background:#f7f7fa;padding:32px 0;">
                <div style="max-width:420px;margin:0 auto;background:#fff;border-radius:12px;box-shadow:0 2px 16px rgba(102,126,234,0.10);padding:32px 28px 28px 28px;font-family:\'Segoe UI\',Arial,sans-serif;">
                    <div style="text-align:center;margin-bottom:18px;">
                        <img src="https://i.imgur.com/8oQw7Qz.png" alt="Tlantic" style="height:48px;">
                    </div>
                    <h2 style="color:#667eea;text-align:center;margin-bottom:18px;font-weight:700;letter-spacing:0.5px;font-size:1.2rem;">Pedido de Alteração ' . $statusTxt . '</h2>
                    <p style="color:#333;text-align:center;font-size:1.05rem;margin-bottom:18px;">
                        Olá <b>' . htmlspecialchars($colaborador['username'] ?? '') . '</b>,<br>
                        O seu pedido de alteração foi <span style="color:' . $corStatus . ';font-weight:600;">' . $statusTxt . '</span> pelo RH.
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
            </div>
            ';

            $mail->AltBody = "O seu pedido de alteração foi $statusTxt pelo RH.\nCampo: $campo\nDe: $valorAntigo\nPara: $valorNovo\nAcesse o portal para mais informações.";

            $mail->send();
        } catch (Exception $e) {
            // Logar erro se necessário
        }
    }
}