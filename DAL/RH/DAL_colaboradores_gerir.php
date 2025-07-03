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
}
?>