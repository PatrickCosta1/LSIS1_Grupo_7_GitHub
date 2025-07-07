<?php
require_once __DIR__ . '/../../DAL/RH/DAL_colaboradores_gerir.php';

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

<<<<<<< Updated upstream
    public function criarUtilizadorConvidado($username, $perfil_convidado, $password) {
        return $this->dal->criarUtilizadorConvidado($username, $perfil_convidado, $password);
=======
    public function criarUtilizadorConvidado($nome, $emailPessoal, $dataInicioContrato, $perfilDestinoId) {
        try {
            $token = bin2hex(random_bytes(24)); // Token mais longo para segurança
            
            // Criar utilizador temporário
            $utilizadorId = $this->dal->criarUtilizador($nome, $emailPessoal, $dataInicioContrato, $perfilDestinoId, $token);
            
            if ($utilizadorId) {
                // Criar entrada na tabela onboarding_temp
                $onboardingId = $this->dal->criarOnboardingTemp($utilizadorId, $token);
                
                if ($onboardingId) {
                    // Enviar email de onboarding
                    require_once __DIR__ . '/../Comuns/BLL_notificacoes.php';
                    $notBLL = new NotificacoesManager();
                    
                    // Corrigir o link para ambiente local correto
                    $linkOnboarding = "http://localhost/LSIS1_Grupo_7_GitHub/UI/Convidado/onboarding_convidado.php?token=" . $token;
                    
                    $assunto = "Bem-vindo à Tlantic - Complete o seu registo";
                    $mensagem = "
                        <div style='font-family:Segoe UI,Arial,sans-serif;background:#f7faff;padding:32px 0;'>
                            <div style='max-width:420px;margin:0 auto;background:#fff;border-radius:14px;box-shadow:0 2px 12px #0360e91a;padding:32px 28px;'>
                                <img src='https://www.tlantic.com/wp-content/uploads/2021/03/logo-tlantic.png' alt='Tlantic' style='height:38px;display:block;margin:0 auto 18px auto;'>
                                <h2 style='color:#0360e9;text-align:center;margin-bottom:18px;font-size:1.2rem;'>Bem-vindo à equipa Tlantic!</h2>
                                <p style='color:#23408e;font-size:1.05rem;text-align:center;'>Para completar o seu processo de integração, clique no botão abaixo:</p>
                                <div style='text-align:center;margin:22px 0;'>
                                    <a href='$linkOnboarding' style='background:#0360e9;color:#fff;padding:12px 28px;border-radius:8px;text-decoration:none;font-weight:bold;font-size:1.1rem;'>Completar Onboarding</a>
                                </div>
                                <p style='color:#444;text-align:center;font-size:0.98rem;margin-bottom:18px;'>
                                    Se não conseguir clicar, copie e cole este link no navegador:<br>
                                    <span style='color:#0360e9;font-size:0.95em;'>$linkOnboarding</span>
                                </p>
                                <div style='text-align:center;margin-top:18px;'>
                                    <img src='https://www.tlantic.com/wp-content/uploads/2021/03/logo-tlantic.png' alt='Tlantic' style='height:22px;opacity:0.7;'>
                                </div>
                            </div>
                        </div>
                    ";
                    
                    // Usar enviarEmailSimples para garantir HTML
                    $emailEnviado = $notBLL->enviarEmailSimples($emailPessoal, $assunto, $mensagem);
                    
                    if ($emailEnviado) {
                        error_log("Email de onboarding enviado para: $emailPessoal");
                    } else {
                        error_log("Falha no envio do email para: $emailPessoal");
                    }
                    
                    return true;
                }
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log("Erro no processo de onboarding: " . $e->getMessage());
            return false;
        }
>>>>>>> Stashed changes
    }

    public function criarOnboardingTemp($dados, $token) {
        return $this->dal->criarOnboardingTemp($dados, $token);
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