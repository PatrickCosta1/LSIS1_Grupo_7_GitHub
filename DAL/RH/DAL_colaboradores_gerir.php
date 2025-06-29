<?php
require_once __DIR__ . '/../Database.php';

class DAL_ColaboradoresGerir {
    public function getAllColaboradores($excludeUserId = null) {
        $pdo = Database::getConnection();
        $sql = "SELECT c.id, c.nome, c.cargo, e.nome as equipa, u.email, u.ativo, u.username, p.nome as perfil
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
}
?>