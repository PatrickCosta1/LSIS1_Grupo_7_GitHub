<?php
require_once __DIR__ . '/../Database.php';

class DAL_ColaboradoresGerir {
    public function getAllColaboradores($excludeUserId = null) {
        $pdo = Database::getConnection();
        $sql = "SELECT c.id, c.nome, c.funcao, e.nome as equipa, u.email, u.ativo, u.username, p.nome as perfil
                FROM colaboradores c
                LEFT JOIN equipa_colaboradores ec ON c.id = ec.colaborador_id
                LEFT JOIN equipas e ON ec.equipa_id = e.id
                LEFT JOIN utilizadores u ON c.utilizador_id = u.id
                LEFT JOIN perfis p ON u.perfil_id = p.id";
        $params = [];
        if ($excludeUserId !== null) {
            $sql .= " WHERE u.id <> ?";
            $params[] = $excludeUserId;
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function addColaborador($dados) {
        $pdo = Database::getConnection();
        // Criar utilizador
        $stmtUser = $pdo->prepare("INSERT INTO utilizadores (username, email, perfil_id, ativo, password) VALUES (?, ?, ?, ?, ?)");
        $okUser = $stmtUser->execute([
            $dados['username'],
            $dados['email'],
            $dados['perfil_id'],
            $dados['ativo'],
            $dados['password']
        ]);
        if (!$okUser) return false;
        $userId = $pdo->lastInsertId();

        // Inserir colaborador com todos os campos relevantes
        $stmtColab = $pdo->prepare("
            INSERT INTO colaboradores (
                utilizador_id, nome, nome_abreviado, morada, morada_fiscal, localidade, codigo_postal, estado_civil, comprovativo_estado_civil,
                habilitacoes, curso, contacto_emergencia, grau_relacionamento, contacto_emergencia_numero, matricula_viatura, data_nascimento,
                data_entrada, data_fim, genero, funcao, geografia, nivel_hierarquico, remuneracao, nif, niss, cc, nacionalidade, situacao_irs,
                dependentes, irs_jovem, primeiro_ano_descontos, telemovel, iban, cartao_continente, voucher_nos, tipo_contrato, regime_horario
            ) VALUES (
                ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?
            )
        ");
        return $stmtColab->execute([
            $userId,
            $dados['nome'] ?? null,
            $dados['nome_abreviado'] ?? null,
            $dados['morada'] ?? null,
            $dados['morada_fiscal'] ?? null,
            $dados['localidade'] ?? null,
            $dados['codigo_postal'] ?? null,
            $dados['estado_civil'] ?? null,
            $dados['comprovativo_estado_civil'] ?? null,
            $dados['habilitacoes'] ?? null,
            $dados['curso'] ?? null,
            $dados['contacto_emergencia'] ?? null,
            $dados['grau_relacionamento'] ?? null,
            $dados['contacto_emergencia_numero'] ?? null,
            $dados['matricula_viatura'] ?? null,
            $dados['data_nascimento'] ?? null,
            $dados['data_entrada'] ?? null,
            $dados['data_fim'] ?? null,
            $dados['genero'] ?? null,
            $dados['funcao'] ?? null,
            $dados['geografia'] ?? null,
            $dados['nivel_hierarquico'] ?? null,
            $dados['remuneracao'] ?? null,
            $dados['nif'] ?? null,
            $dados['niss'] ?? null,
            $dados['cc'] ?? null,
            $dados['nacionalidade'] ?? null,
            $dados['situacao_irs'] ?? null,
            $dados['dependentes'] ?? null,
            $dados['irs_jovem'] ?? null,
            $dados['primeiro_ano_descontos'] ?? null,
            $dados['telemovel'] ?? null,
            $dados['iban'] ?? null,
            $dados['cartao_continente'] ?? null,
            $dados['voucher_nos'] ?? null,
            $dados['tipo_contrato'] ?? null,
            $dados['regime_horario'] ?? null
        ]);
    }

    public function getColaboradoresByPerfil($perfilNome) {
        $pdo = Database::getConnection();
        $sql = "SELECT c.id, c.nome, c.funcao, e.nome as equipa, u.email, u.ativo, u.username, p.nome as perfil
                FROM colaboradores c
                LEFT JOIN equipa_colaboradores ec ON c.id = ec.colaborador_id
                LEFT JOIN equipas e ON ec.equipa_id = e.id
                LEFT JOIN utilizadores u ON c.utilizador_id = u.id
                LEFT JOIN perfis p ON u.perfil_id = p.id
                WHERE LOWER(p.nome) = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([strtolower($perfilNome)]);
        return $stmt->fetchAll();
    }

    public function getColaboradoresApenasColaboradores() {
        $pdo = Database::getConnection();
        // Mostra apenas colaboradores cujo perfil é 'colaborador' (case insensitive)
        $sql = "SELECT c.id, c.nome, c.funcao, e.nome as equipa, u.email, u.ativo, u.username, p.nome as perfil
                FROM colaboradores c
                LEFT JOIN equipa_colaboradores ec ON c.id = ec.colaborador_id
                LEFT JOIN equipas e ON ec.equipa_id = e.id
                LEFT JOIN utilizadores u ON c.utilizador_id = u.id
                LEFT JOIN perfis p ON u.perfil_id = p.id
                WHERE LOWER(p.nome) = 'colaborador'";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }
}
?>
