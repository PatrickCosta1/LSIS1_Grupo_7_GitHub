<?php
require_once __DIR__ . '/../../DAL/RH/DAL_colaboradores_gerir.php';
// Inclua o autoload do Composer no topo para garantir PHPMailer disponível
require_once __DIR__ . '/../../vendor/autoload.php';

class RHColaboradoresManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_ColaboradoresGerir();
    }

    public function getAllColaboradores($excludeUserId = null) {
        return $this->dal->getAllColaboradores($excludeUserId);
    }

    public function addColaborador($dados) {
        return $this->dal->addColaborador($dados);
    }

    public function getAllEquipas() {
        return $this->dal->getAllEquipas();
    }

    public function getAllPerfis() {
        return $this->dal->getAllPerfis();
    }

    public function getColaboradoresPorEquipa($equipaId) {
        return $this->dal->getColaboradoresPorEquipa($equipaId);
    }

    public function getColaboradoresPorPerfil($perfilId) {
        return $this->dal->getColaboradoresPorPerfil($perfilId);
    }

    public function criarUtilizadorConvidado($nome, $emailPessoal, $dataInicioContrato, $perfilDestinoId) {
        // Log início do processo
        error_log("[CONVIDADO] Início criarUtilizadorConvidado para $emailPessoal");
        $token = bin2hex(random_bytes(24));
        $username = strtolower(preg_replace('/[^a-z0-9]/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $nome))) . rand(100,999);
        $password = bin2hex(random_bytes(4));
        $perfil_convidado = 1; // ID do perfil 'convidado', ajuste conforme necessário

        // Validação de email
        $emailPessoal = trim($emailPessoal);
        if (!filter_var($emailPessoal, FILTER_VALIDATE_EMAIL)) {
            error_log("[CONVIDADO] Email inválido: $emailPessoal");
            return false;
        }

        $utilizadorId = $this->dal->criarUtilizadorConvidado($username, $perfil_convidado, $password);
        if (!$utilizadorId) {
            error_log("[CONVIDADO] Falha ao criar utilizador convidado na DAL");
            return false;
        }

        $dados = [
            'nome' => $nome,
            'email_pessoal' => $emailPessoal,
            'data_inicio_contrato' => $dataInicioContrato,
            'perfil_destino_id' => $perfilDestinoId,
            'token' => $token,
            'utilizador_id' => $utilizadorId
        ];
        $ok = $this->dal->criarOnboardingTemp($dados);
        if (!$ok) {
            error_log("[CONVIDADO] Falha ao criar onboarding_temp na DAL");
            return false;
        }

        // Montar email
        $link = "http://localhost/LSIS1_Grupo_7_GitHub/UI/Convidado/onboarding_convidado.php?token=$token";
        $ano = date('Y');
        $body = '
        <div style="background:#f7f7fa;padding:32px 0;">
            <div style="max-width:420px;margin:0 auto;background:#fff;border-radius:12px;box-shadow:0 2px 16px rgba(102,126,234,0.10);padding:32px 28px 28px 28px;font-family:\'Segoe UI\',Arial,sans-serif;">
                <div style="text-align:center;margin-bottom:18px;">
                    <img src="https://i.imgur.com/8oQw7Qz.png" alt="Tlantic" style="height:48px;">
                </div>
                <h2 style="color:#667eea;text-align:center;margin-bottom:18px;font-weight:700;letter-spacing:0.5px;font-size:1.2rem;">Onboarding - Portal Tlantic</h2>
                <div style="color:#333;text-align:center;font-size:1.05rem;margin-bottom:24px;">
                    Olá <b>' . htmlspecialchars($nome) . '</b>,<br><br>
                    Foi iniciado o seu processo de onboarding na Tlantic.<br>
                    Por favor, aceda ao seguinte link para preencher os seus dados:<br>
                    <a href="' . $link . '" style="color:#0360e9;text-decoration:underline;word-break:break-all;">' . $link . '</a><br><br>
                    Obrigado.
                </div>
                <div style="margin-top:32px;text-align:center;color:#aaa;font-size:0.90rem;">
                    &copy; ' . $ano . ' Tlantic. Todos os direitos reservados.
                </div>
            </div>
        </div>
        ';

        // Enviar email com PHPMailer
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'suportetlantic@gmail.com';
            $mail->Password   = 'qfas jxch tmub iboy';
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port= 587;

            $mail->setFrom('suportetlantic@gmail.com', 'Portal Tlantic');
            $mail->addAddress($emailPessoal);
            $mail->isHTML(true);
            $mail->Subject = 'Notificação - Portal Tlantic';
            $mail->Body = $body;
            $mail->AltBody = "Olá $nome,\n\nFoi iniciado o seu processo de onboarding na Tlantic. Aceda ao link: $link\n\nObrigado.";

            $mail->send();
            error_log("[CONVIDADO] Email enviado com sucesso para $emailPessoal");
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            error_log("[CONVIDADO] Erro PHPMailer: " . $e->getMessage());
            return false;
        }
        return true;
    }

    public function criarOnboardingTemp($dados) {
        return $this->dal->criarOnboardingTemp($dados);
    }

    public function getOnboardingTempByToken($token) {
        return $this->dal->getOnboardingTempByToken($token);
    }

    public function submeterOnboardingTemp($token, $dados) {
        return $this->dal->submeterOnboardingTemp($token, $dados);
    }

    public function listarOnboardingsPendentes() {
        return $this->dal->listarOnboardingsPendentes();
    }

    public function aprovarOnboarding($token) {
        return $this->dal->aprovarOnboarding($token);
    }

    public function recusarOnboarding($token) {
        return $this->dal->recusarOnboarding($token);
    }

    public function removerColaboradorComUtilizador($colaboradorId) {
        return $this->dal->removerColaboradorComUtilizador($colaboradorId);
    }

    public function importarColaboradoresCSV($csvPath)
    {
        require_once __DIR__ . '/../../DAL/Database.php';
        $pdo = Database::getConnection();
        $handle = fopen($csvPath, "r");
        if (!$handle) return false;

        $header = fgetcsv($handle, 0, ";");
        if (!$header) return false;

        $importados = [];
        while (($row = fgetcsv($handle, 0, ";")) !== false) {
            $data = array_combine($header, $row);

            // Geração de email institucional (exemplo: nome.apelido@empresa.com)
            $nome = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $data['nome'] ?? ''));
            $apelido = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $data['apelido'] ?? ''));
            $email_institucional = $nome . '.' . $apelido . '@empresa.com';

            // Username baseado no nome/apelido
            $username = $nome . '.' . $apelido;
            // Senha aleatória ou baseada no nome
            $senha = substr($nome, 0, 3) . substr($apelido, 0, 3) . rand(100, 999);

            // Verifica se já existe utilizador com este email
            $stmt = $pdo->prepare("SELECT id FROM utilizadores WHERE email = ?");
            $stmt->execute([$email_institucional]);
            if ($rowUtil = $stmt->fetch()) {
                // Já existe utilizador, mas pode não existir colaborador!
                $utilizador_id = $rowUtil['id'];
                // Verifica se já existe colaborador para este utilizador
                $stmt2 = $pdo->prepare("SELECT id FROM colaboradores WHERE utilizador_id = ?");
                $stmt2->execute([$utilizador_id]);
                if ($stmt2->fetch()) {
                    continue; // Já existe colaborador, ignora
                }
            } else {
                // Cria utilizador
                $stmt = $pdo->prepare("INSERT INTO utilizadores (email, username, password, perfil_id, ativo, data_criacao) VALUES (?, ?, ?, ?, 1, NOW())");
                $perfil_id = 3; // perfil_id para colaborador (ajuste conforme o seu sistema)
                $stmt->execute([
                    $email_institucional,
                    $username,
                    password_hash($senha, PASSWORD_DEFAULT),
                    $perfil_id
                ]);
                $utilizador_id = $pdo->lastInsertId();
            }

            // Cria colaborador (sempre que não existir)
            $stmt = $pdo->prepare("INSERT INTO colaboradores 
                (utilizador_id, nome, apelido, email_pessoal, email_institucional, data_nascimento, nif, niss, morada, localidade, codigo_postal, telemovel, sexo, estado_civil, habilitacoes, curso, iban, nome_contacto_emergencia, grau_relacionamento, contacto_emergencia)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $utilizador_id,
                $data['nome'] ?? '',
                $data['apelido'] ?? '',
                $data['email_pessoal'] ?? '',
                $email_institucional,
                $data['data_nascimento'] ?? null,
                $data['nif'] ?? null,
                $data['niss'] ?? null,
                $data['morada'] ?? '',
                $data['localidade'] ?? '',
                $data['codigo_postal'] ?? '',
                $data['telemovel'] ?? '',
                $data['sexo'] ?? '',
                $data['estado_civil'] ?? '',
                $data['habilitacoes'] ?? '',
                $data['curso'] ?? '',
                $data['iban'] ?? '',
                $data['nome_contacto_emergencia'] ?? '',
                $data['grau_relacionamento'] ?? '',
                $data['contacto_emergencia'] ?? ''
            ]);

            // Envia email com dados de acesso
            $email_destino = $data['email_pessoal'] ?? $email_institucional;
            $this->enviarEmailAcessoColaborador($email_destino, $username, $senha, $email_institucional);

            $importados[] = [
                'nome' => $data['nome'] ?? '',
                'apelido' => $data['apelido'] ?? '',
                'email' => $email_institucional,
                'username' => $username,
                'senha' => $senha
            ];
        }
        fclose($handle);
        return $importados;
    }

    private function enviarEmailAcessoColaborador($email, $username, $senha, $email_institucional)
    {
        // ...implemente o envio de email conforme o seu sistema...
        $assunto = "Acesso ao Portal Tlantic";
        $mensagem = "Bem-vindo ao Portal Tlantic!\n\n".
            "O seu acesso foi criado:\n".
            "Email institucional: $email_institucional\n".
            "Username: $username\n".
            "Senha: $senha\n\n".
            "Por favor, altere a senha após o primeiro login.";
        // mail($email, $assunto, $mensagem); // descomente e ajuste conforme necessário
    }

    public function criarUtilizadorComPerfil($username, $perfil_id, $password, $email)
    {
        require_once __DIR__ . '/../../DAL/Database.php';
        $pdo = Database::getConnection();
        // Verifica se já existe email
        $stmt = $pdo->prepare("SELECT id FROM utilizadores WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return false;
        }
        // Salva a password em texto simples (NÃO recomendado para produção!)
        $stmt = $pdo->prepare("INSERT INTO utilizadores (username, perfil_id, password, email, ativo, data_criacao) VALUES (?, ?, ?, ?, 1, NOW())");
        $stmt->execute([
            $username,
            $perfil_id,
            $password, // texto simples
            $email
        ]);
        return $pdo->lastInsertId();
    }
}
?>