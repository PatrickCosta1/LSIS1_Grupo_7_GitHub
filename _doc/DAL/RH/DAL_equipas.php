<?php
require_once __DIR__ . '/../Database.php';

class DAL_Equipas {
    public function getAllEquipas() {
        $pdo = Database::getConnection();
        $sql = "SELECT e.id, e.nome, c.nome as responsavel, 
                       (SELECT COUNT(*) FROM equipa_colaboradores ec WHERE ec.equipa_id = e.id) as num_colaboradores
                FROM equipas e
                LEFT JOIN colaboradores c ON e.responsavel_id = c.id";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function addEquipa($nome, $responsavel_id, $elementos = [], $tipo = 'colaboradores') {
        $pdo = Database::getConnection();
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("INSERT INTO equipas (nome, responsavel_id, tipo) VALUES (?, ?, ?)");
            $stmt->execute([$nome, $responsavel_id, $tipo]);
            $equipa_id = $pdo->lastInsertId();

            // Inserir elementos na tabela equipa_colaboradores
            if (!empty($elementos)) {
                $stmtElem = $pdo->prepare("INSERT INTO equipa_colaboradores (equipa_id, colaborador_id) VALUES (?, ?)");
                foreach ($elementos as $colab_id) {
                    $stmtElem->execute([$equipa_id, $colab_id]);
                }
            }
            $pdo->commit();
            return true;
        } catch (\Exception $e) {
            $pdo->rollBack();
            return $e->getMessage();
        }
    }

    public function getCoordenadores() {
        $pdo = Database::getConnection();
        $sql = "SELECT c.nome, c.id as colaborador_id
                FROM colaboradores c
                INNER JOIN utilizadores u ON c.utilizador_id = u.id
                WHERE u.perfil_id = 3 AND u.ativo = 1";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getColaboradores() {
        $pdo = Database::getConnection();
        $sql = "SELECT c.id as colaborador_id, c.nome
                FROM colaboradores c
                INNER JOIN utilizadores u ON c.utilizador_id = u.id
                WHERE u.perfil_id = 2 AND u.ativo = 1";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getEquipaById($id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM equipas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCoordenadoresDisponiveis($equipaId = null) {
        $pdo = Database::getConnection();
        $sql = "SELECT c.id, c.nome FROM colaboradores c
                INNER JOIN utilizadores u ON c.utilizador_id = u.id
                WHERE c.cargo = 'Coordenador' AND u.perfil_id = 3 AND u.ativo = 1
                " . ($equipaId ? "OR c.id = (SELECT responsavel_id FROM equipas WHERE id = ?)" : "");
        $stmt = $pdo->prepare($sql);
        if ($equipaId) {
            $stmt->execute([$equipaId]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getColaboradoresDaEquipa($equipaId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT c.id, c.nome FROM equipa_colaboradores ec
                               INNER JOIN colaboradores c ON ec.colaborador_id = c.id
                               WHERE ec.equipa_id = ?");
        $stmt->execute([$equipaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>