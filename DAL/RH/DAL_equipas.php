<?php
require_once __DIR__ . '/../Database.php';

class DAL_Equipas {
    public function getAllEquipas() {
        $pdo = Database::getConnection();
        $sql = "SELECT e.id, e.nome, c.nome as coordenador, 
                       (SELECT COUNT(*) FROM equipa_colaboradores ec WHERE ec.equipa_id = e.id) as num_colaboradores
                FROM equipas e
                LEFT JOIN colaboradores c ON e.coordenador_id = c.utilizador_id";
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

        // Inserir elementos na tabela equipa_colaboradores
        if (!empty($elementos)) {
            $stmtElem = $pdo->prepare("INSERT INTO equipa_colaboradores (equipa_id, colaborador_id) VALUES (?, ?)");
            foreach ($elementos as $colab_id) {
                $stmtElem->execute([$equipa_id, $colab_id]);
            }
        }
        $pdo->commit();
        return true;
    } catch (\Exception $e) { // <-- FECHO DE CHAVE ANTES DO CATCH!
        $pdo->rollBack();
        return $e->getMessage();
    }
}
    public function getCoordenadores() {
        $pdo = Database::getConnection();
        $sql = "SELECT c.nome, c.utilizador_id
                FROM colaboradores c
                INNER JOIN utilizadores u ON c.utilizador_id = u.id
                WHERE u.perfil_id = 3";
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
}
?>