<?php
require_once __DIR__ . '/../Database.php';

class DAL_ColaboradoresGerir {
    public function getAllColaboradores($excludeUserId = null, $tipoRH = null, $equipa_id = null) {
        $pdo = Database::getConnection();
        $sql = "SELECT c.id, c.nome, c.funcao, e.nome as equipa, u.email, u.ativo, u.username, p.nome as perfil, c.utilizador_id
                FROM colaboradores c
                LEFT JOIN equipa_colaboradores ec ON c.id = ec.colaborador_id
                LEFT JOIN equipas e ON ec.equipa_id = e.id
                LEFT JOIN utilizadores u ON c.utilizador_id = u.id
                LEFT JOIN perfis p ON u.perfil_id = p.id";
        $params = [];
        $where = [];
        if ($excludeUserId !== null) {
            $where[] = "u.id <> ?";
            $params[] = $excludeUserId;
        }
        // RH tipo 1: só pode ver colaboradores (perfil = colaborador)
        if ($tipoRH === 'colaboradores') {
            $where[] = "LOWER(p.nome) = 'colaborador'";
        }
        // RH tipo 2: pode ver todos de uma equipa específica
        if ($tipoRH === 'equipa' && $equipa_id) {
            $where[] = "ec.equipa_id = ?";
            $params[] = $equipa_id;
        }
        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
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

        // Criar colaborador
        $stmtColab = $pdo->prepare("INSERT INTO colaboradores (utilizador_id, nome, funcao, morada, estado_civil, habilitacoes, contacto_emergencia, matricula_viatura, data_nascimento, genero, data_entrada, geografia, nivel_hierarquico, remuneracao) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmtColab->execute([
            $userId,
            $dados['nome'],
            $dados['funcao'],
            $dados['morada'],
            $dados['estado_civil'],
            $dados['habilitacoes'],
            $dados['contacto_emergencia'],
            $dados['matricula_viatura'],
            $dados['data_nascimento'],
            $dados['genero'],
            $dados['data_entrada'],
            $dados['geografia'],
            $dados['nivel_hierarquico'],
            $dados['remuneracao']
        ]);
    }
}
?>
