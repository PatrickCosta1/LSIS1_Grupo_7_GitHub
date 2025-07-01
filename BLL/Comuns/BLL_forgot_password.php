<?php
require_once __DIR__ . '/../../DAL/Comuns/DAL_forgot_password.php';

// Adicione o autoload do Composer para PHPMailer
require_once __DIR__ . '/../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ForgotPasswordManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_ForgotPassword();
    }

    public function emailExiste($email) {
        return $this->dal->emailExiste($email);
    }

    public function enviarCodigo($email) {
        $codigo = random_int(100000, 999999);
        $this->dal->guardarCodigo($email, $codigo);

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'patrickcosta1605@gmail.com';
            $mail->Password   = 'sxcxatuvhpcwgohu'; // senha de app do Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('patrickcosta1605@gmail.com', 'Portal Tlantic');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Recuperação de Palavra-passe - Portal Tlantic';

            $mail->Body = '
            <div style="background:#f7f7fa;padding:32px 0;">
                <div style="max-width:420px;margin:0 auto;background:#fff;border-radius:12px;box-shadow:0 2px 16px rgba(102,126,234,0.10);padding:32px 28px 28px 28px;font-family:\'Segoe UI\',Arial,sans-serif;">
                    <div style="text-align:center;margin-bottom:18px;">
                        <img src="https://i.imgur.com/8oQw7Qz.png" alt="Tlantic" style="height:48px;">
                    </div>
                    <h2 style="color:#667eea;text-align:center;margin-bottom:18px;font-weight:700;letter-spacing:0.5px;font-size:1.4rem;">Recuperação de Palavra-passe</h2>
                    <p style="color:#333;text-align:center;font-size:1.05rem;margin-bottom:24px;">
                        Recebemos um pedido para redefinir a sua palavra-passe no <b>Portal Tlantic</b>.<br>
                        Utilize o código abaixo para continuar o processo:
                    </p>
                    <div style="background:linear-gradient(90deg,#667eea 0%,#764ba2 100%);color:#fff;font-size:2.1rem;font-weight:700;text-align:center;letter-spacing:6px;padding:18px 0;border-radius:8px;margin-bottom:24px;">
                        ' . $codigo . '
                    </div>
                    <p style="color:#444;text-align:center;font-size:0.98rem;margin-bottom:18px;">
                        Este código é válido por <b>15 minutos</b>.<br>
                        Se não solicitou esta recuperação, ignore este email.
                    </p>
                    <div style="text-align:center;margin-top:30px;">
                        <a href="https://www.tlantic.com/" style="color:#667eea;text-decoration:none;font-size:0.95rem;">Portal Tlantic</a>
                    </div>
                    <div style="margin-top:32px;text-align:center;color:#aaa;font-size:0.90rem;">
                        &copy; ' . date('Y') . ' Tlantic. Todos os direitos reservados.
                    </div>
                </div>
            </div>
            ';

            $mail->AltBody = "O seu código de recuperação é: $codigo\nEste código é válido por 15 minutos.\nSe não solicitou, ignore este email.";

            $mail->send();
        } catch (Exception $e) {
            // Pode logar o erro se quiser
        }
    }

    public function verificarCodigo($email, $codigo) {
        return $this->dal->verificarCodigo($email, $codigo);
    }

    public function alterarPassword($email, $novaPassword) {
        return $this->dal->alterarPassword($email, $novaPassword);
    }
}
?>
