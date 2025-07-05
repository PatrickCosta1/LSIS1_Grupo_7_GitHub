<?php
    require_once __DIR__ . '/../Database.php';

    class DAL_ColaboradoresGerir {
        public function getAllColaboradores($excludeUserId = null) {
            $pdo = Database::getConnection();
            // Agrupa por colaborador e concatena os nomes das equipas
            $sql = "SELECT 
                        c.id, 
                        c.nome, 
                        c.cargo, 
                        GROUP_CONCAT(DISTINCT e.nome ORDER BY e.nome SEPARATOR ', ') as equipas, 
                        u.email, 
                        u.ativo, 
                        u.username, 
                        p.nome as perfil
                    FROM colaboradores c
                    LEFT JOIN equipa_colaboradores ec ON c.id = ec.colaborador_id
                    LEFT JOIN equipas e ON ec.equipa_id = e.id
                    LEFT JOIN utilizadores u ON c.utilizador_id = u.id
                    LEFT JOIN perfis p ON u.perfil_id = p.id
                    WHERE u.perfil_id <> 5"; // 5 = admin
            $params = [];
            if ($excludeUserId !== null) {
                $sql .= " AND u.id <> ?";
                $params[] = $excludeUserId;
            }
            $sql .= " GROUP BY c.id, c.nome, c.cargo, u.email, u.ativo, u.username, p.nome";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        }

        public function addColaborador($dados) {
            $pdo = Database::getConnection();
            // Criar utilizador
            $stmtUser = $pdo->prepare("INSERT INTO utilizadores (username, email, ativo, password, perfil_id) VALUES (?, ?, ?, ?, ?)");
            $okUser = $stmtUser->execute([
                $dados['username'],
                $dados['email'],
                $dados['ativo'],
                $dados['password'],
                $dados['perfil_id'] // este valor tem de vir do formulário/processamento
            ]);
            if (!$okUser) return false;
            $userId = $pdo->lastInsertId();

            // Criar colaborador
            $stmtColab = $pdo->prepare("INSERT INTO colaboradores (utilizador_id, nome, cargo, nivel_hierarquico) VALUES (?, ?, ?, ?)");
            return $stmtColab->execute([
                $userId,
                $dados['nome'],
                $dados['cargo'],
                $dados['nivel_hierarquico']
            ]);
        }

        public function getAllEquipas() {
            $pdo = Database::getConnection();
            $stmt = $pdo->query("SELECT id, nome FROM equipas ORDER BY nome");
            return $stmt->fetchAll();
        }

        public function getAllPerfis() {
            $pdo = Database::getConnection();
            $stmt = $pdo->query("SELECT id, nome FROM perfis ORDER BY nome");
            return $stmt->fetchAll();
        }

        public function getColaboradoresPorEquipa($equipaId) {
            $pdo = Database::getConnection();
            // Seleciona todos os colaboradores que pertencem à equipa indicada
            $sql = "SELECT 
                        c.id, 
                        c.nome, 
                        c.cargo, 
                        (
                            SELECT GROUP_CONCAT(DISTINCT e2.nome ORDER BY e2.nome SEPARATOR ', ')
                            FROM equipa_colaboradores ec2
                            INNER JOIN equipas e2 ON ec2.equipa_id = e2.id
                            WHERE ec2.colaborador_id = c.id
                        ) as equipas,
                        u.email, 
                        u.ativo, 
                        u.username, 
                        p.nome as perfil
                    FROM colaboradores c
                    INNER JOIN equipa_colaboradores ec ON c.id = ec.colaborador_id
                    LEFT JOIN utilizadores u ON c.utilizador_id = u.id
                    LEFT JOIN perfis p ON u.perfil_id = p.id
                    WHERE ec.equipa_id = ? AND (u.perfil_id IS NULL OR u.perfil_id <> 5)
                    GROUP BY c.id, c.nome, c.cargo, u.email, u.ativo, u.username, p.nome";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$equipaId]);
            return $stmt->fetchAll();
        }

        public function getColaboradoresPorPerfil($perfilId) {
            $pdo = Database::getConnection();
            $sql = "SELECT 
                        c.id, 
                        c.nome, 
                        c.cargo, 
                        GROUP_CONCAT(DISTINCT e.nome ORDER BY e.nome SEPARATOR ', ') as equipas, 
                        u.email, 
                        u.ativo, 
                        u.username, 
                        p.nome as perfil
                    FROM colaboradores c
                    LEFT JOIN equipa_colaboradores ec ON c.id = ec.colaborador_id
                    LEFT JOIN equipas e ON ec.equipa_id = e.id
                    LEFT JOIN utilizadores u ON c.utilizador_id = u.id
                    LEFT JOIN perfis p ON u.perfil_id = p.id
                    WHERE u.perfil_id = ?
                    GROUP BY c.id, c.nome, c.cargo, u.email, u.ativo, u.username, p.nome";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$perfilId]);
            return $stmt->fetchAll();
        }

        public function criarUtilizadorConvidado($username, $perfil_convidado, $password) {
            $pdo = Database::getConnection();
            // Gera um email temporário único para evitar duplicidade
            $email_temp = 'convidado_' . uniqid() . '@temp.tlantic.com';
            $stmt = $pdo->prepare("INSERT INTO utilizadores (username, perfil_id, ativo, password, email) VALUES (?, ?, 1, ?, ?)");
            $ok = $stmt->execute([$username, $perfil_convidado, $password, $email_temp]);
            return $ok ? $pdo->lastInsertId() : false;
        }

        public function criarOnboardingTemp($dados) {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("INSERT INTO onboarding_temp (nome, email_pessoal, data_inicio_contrato, perfil_destino_id, token, utilizador_id) VALUES (?, ?, ?, ?, ?, ?)");
            return $stmt->execute([
                $dados['nome'],
                $dados['email_pessoal'],
                $dados['data_inicio_contrato'],
                $dados['perfil_destino_id'],
                $dados['token'],
                $dados['utilizador_id']
            ]);
        }

        public function getOnboardingTempByToken($token) {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT * FROM onboarding_temp WHERE token = ?");
            $stmt->execute([$token]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function submeterOnboardingTemp($token, $dados) {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("UPDATE onboarding_temp SET dados_json = ?, estado = 'submetido', atualizado_em = NOW() WHERE token = ?");
            return $stmt->execute([json_encode($dados), $token]);
        }

        public function listarOnboardingsPendentes() {
            $pdo = Database::getConnection();
            $stmt = $pdo->query("SELECT * FROM onboarding_temp WHERE estado = 'submetido'");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function aprovarOnboarding($token) {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT * FROM onboarding_temp WHERE token = ? AND estado = 'submetido'");
            $stmt->execute([$token]);
            $ob = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$ob) return false;

            $dados = json_decode($ob['dados_json'], true);
            if (!$dados) return false;

            // Gerar email da empresa e username
            $username = strtolower(preg_replace('/[^a-z0-9]/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $ob['nome']))) . rand(100,999);
            $email_empresa = $username . '@tlantic.com';
            $password = bin2hex(random_bytes(4));

            // Atualizar utilizador para perfil destino e email/username
            $stmtU = $pdo->prepare("UPDATE utilizadores SET username=?, email=?, perfil_id=?, password=? WHERE id=?");
            $stmtU->execute([$username, $email_empresa, $ob['perfil_destino_id'], $password, $ob['utilizador_id']]);

            // Criar colaborador com todos os dados
            $stmtC = $pdo->prepare("INSERT INTO colaboradores (utilizador_id, nome, apelido, data_nascimento, morada, localidade, codigo_postal, telemovel, sexo, estado_civil, habilitacoes, curso, nif, niss, iban, nome_contacto_emergencia, grau_relacionamento, contacto_emergencia, data_inicio_contrato) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmtC->execute([
                $ob['utilizador_id'],
                $dados['nome'], $dados['apelido'], $dados['data_nascimento'], $dados['morada'], $dados['localidade'], $dados['codigo_postal'],
                $dados['telemovel'], $dados['sexo'], $dados['estado_civil'], $dados['habilitacoes'], $dados['curso'],
                $dados['nif'], $dados['niss'], $dados['iban'],
                $dados['nome_contacto_emergencia'], $dados['grau_relacionamento'], $dados['contacto_emergencia'],
                $ob['data_inicio_contrato']
            ]);

            // Atualizar estado do onboarding
            $pdo->prepare("UPDATE onboarding_temp SET estado = 'aceite' WHERE token = ?")->execute([$token]);

            // Enviar email ao colaborador com credenciais (HTML estilizado)
            require_once __DIR__ . '/../../BLL/Comuns/BLL_notificacoes.php';
            $notBLL = new \NotificacoesManager();
            $msg = '
            <div style="font-family:Segoe UI,Arial,sans-serif;background:#f7faff;padding:32px 0;">
                <div style="max-width:420px;margin:0 auto;background:#fff;border-radius:14px;box-shadow:0 2px 12px #0360e91a;padding:32px 28px;">
                    <img src="https://www.tlantic.com/wp-content/uploads/2021/03/logo-tlantic.png" alt="Tlantic" style="height:38px;display:block;margin:0 auto 18px auto;">
                    <h2 style="color:#0360e9;text-align:center;margin-bottom:18px;font-size:1.2rem;">Onboarding aceite</h2>
                    <p style="color:#23408e;font-size:1.05rem;text-align:center;">Bem-vindo à equipa! O seu onboarding foi aceite.<br>Estes são os seus dados de acesso:</p>
                    <div style="background:#f7faff;border-radius:8px;padding:18px 16px;margin:18px 0 22px 0;">
                        <div style="margin-bottom:8px;"><b style="color:#0360e9;">Username:</b> '.$username.'</div>
                        <div style="margin-bottom:8px;"><b style="color:#0360e9;">Email:</b> '.$email_empresa.'</div>
                        <div><b style="color:#0360e9;">Password:</b> '.$password.'</div>
                    </div>
                    <p style="color:#23408e;font-size:0.98rem;text-align:center;">Aceda ao <a href="http://localhost/LSIS1_Grupo_7_GitHub/" style="color:#0360e9;text-decoration:underline;">Portal Tlantic</a> para começar.</p>
                    <div style="text-align:center;margin-top:18px;">
                        <img src="https://www.tlantic.com/wp-content/uploads/2021/03/logo-tlantic.png" alt="Tlantic" style="height:22px;opacity:0.7;">
                    </div>
                </div>
            </div>';
            $notBLL->enviarEmailSimples($ob['email_pessoal'], "Onboarding aceite - Portal Tlantic", $msg);

            return true;
        }

        public function recusarOnboarding($token) {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT * FROM onboarding_temp WHERE token = ?");
            $stmt->execute([$token]);
            $ob = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$ob) return false;

            // Apagar utilizador convidado
            $pdo->prepare("DELETE FROM utilizadores WHERE id = ?")->execute([$ob['utilizador_id']]);
            // Apagar onboarding
            $pdo->prepare("DELETE FROM onboarding_temp WHERE token = ?")->execute([$token]);

            // Enviar email de recusa (HTML estilizado)
            require_once __DIR__ . '/../../BLL/Comuns/BLL_notificacoes.php';
            $notBLL = new \NotificacoesManager();
            $msg = '
            <div style="font-family:Segoe UI,Arial,sans-serif;background:#f7faff;padding:32px 0;">
                <div style="max-width:420px;margin:0 auto;background:#fff;border-radius:14px;box-shadow:0 2px 12px #e53e3e1a;padding:32px 28px;">
                    <img src="https://www.tlantic.com/wp-content/uploads/2021/03/logo-tlantic.png" alt="Tlantic" style="height:38px;display:block;margin:0 auto 18px auto;">
                    <h2 style="color:#e53e3e;text-align:center;margin-bottom:18px;font-size:1.2rem;">Onboarding recusado</h2>
                    <p style="color:#23408e;font-size:1.05rem;text-align:center;">O seu onboarding foi <b style="color:#e53e3e;">recusado</b>.<br>Para mais informações, contacte o RH.</p>
                    <div style="text-align:center;margin-top:18px;">
                        <img src="https://www.tlantic.com/wp-content/uploads/2021/03/logo-tlantic.png" alt="Tlantic" style="height:22px;opacity:0.7;">
                    </div>
                </div>
            </div>';
            $notBLL->enviarEmailSimples($ob['email_pessoal'], "Onboarding recusado - Portal Tlantic", $msg);

            return true;
        }

        public function removerColaboradorComUtilizador($colaboradorId) {
            $pdo = Database::getConnection();
            // Buscar utilizador_id associado
            $stmt = $pdo->prepare("SELECT utilizador_id FROM colaboradores WHERE id = ?");
            $stmt->execute([$colaboradorId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return false;
            $utilizadorId = $row['utilizador_id'];

            // Remover colaborador
            $pdo->prepare("DELETE FROM colaboradores WHERE id = ?")->execute([$colaboradorId]);
            // Remover utilizador
            $pdo->prepare("DELETE FROM utilizadores WHERE id = ?")->execute([$utilizadorId]);
            return true;
        }
    }
    ?>