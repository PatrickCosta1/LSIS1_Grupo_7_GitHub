<?php
require_once __DIR__ . '/../Database.php';

class DAL_EquipaNova {
    public function getCoordenadores() {
        $pdo = Database::getConnection();
        $sql = "SELECT c.nome, c.utilizador_id
                FROM colaboradores c
                INNER JOIN utilizadores u ON c.utilizador_id = u.id
                WHERE u.perfil_id = 3 AND u.ativo = 1";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getColaboradoresSemEquipa() {
        $pdo = Database::getConnection();
        $sql = "SELECT c.id as colaborador_id, c.nome
                FROM colaboradores c
                INNER JOIN utilizadores u ON c.utilizador_id = u.id
                WHERE u.perfil_id = 2
                  AND u.ativo = 1
                  AND c.id NOT IN (
                      SELECT colaborador_id FROM equipa_colaboradores
                  )";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }
    public function getColaboradoresSemEquipaSoColaboradores() {
        $pdo = Database::getConnection();
        $sql = "SELECT u.id as utilizador_id, u.nome
                FROM utilizadores u
                WHERE u.perfil_id = 2
                  AND u.ativo = 1
                  AND u.id NOT IN (
                      SELECT c.utilizador_id
                      FROM equipa_colaboradores ec
                      INNER JOIN colaboradores c ON ec.colaborador_id = c.id
                  )";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function addEquipa($nome, $coordenador_utilizador_id, $elementos = []) {
        $pdo = Database::getConnection();
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("INSERT INTO equipas (nome, coordenador_id) VALUES (?, ?)");
            $stmt->execute([$nome, $coordenador_utilizador_id]);
            $equipa_id = $pdo->lastInsertId();

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
}
?>