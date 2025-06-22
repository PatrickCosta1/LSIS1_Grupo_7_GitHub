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

    public function addEquipa($nome, $coordenador_utilizador_id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO equipas (nome, coordenador_id) VALUES (?, ?)");
        return $stmt->execute([$nome, $coordenador_utilizador_id]);
    }

    public function getCoordenadores() {
        $pdo = Database::getConnection();
        $sql = "SELECT c.nome, u.id as utilizador_id
                FROM colaboradores c
                INNER JOIN utilizadores u ON c.utilizador_id = u.id
                INNER JOIN perfis p ON u.perfil_id = p.id
                WHERE LOWER(p.nome) = 'coordenador'";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getEquipaById($id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM equipas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getColaboradoresByEquipa($equipa_id) {
        $pdo = Database::getConnection();
        $sql = "SELECT c.id, c.nome, c.funcao
                FROM colaboradores c
                INNER JOIN equipa_colaboradores ec ON c.id = ec.colaborador_id
                WHERE ec.equipa_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$equipa_id]);
        return $stmt->fetchAll();
    }

    public function atualizarColaboradoresEquipa($equipa_id, $colaboradores_ids) {
        $pdo = Database::getConnection();
        // Remove todos os colaboradores desta equipa
        $pdo->prepare("DELETE FROM equipa_colaboradores WHERE equipa_id = ?")->execute([$equipa_id]);
        // Para cada colaborador, garantir que não está noutra equipa
        foreach ($colaboradores_ids as $colab_id) {
            // Remove de outras equipas
            $pdo->prepare("DELETE FROM equipa_colaboradores WHERE colaborador_id = ?")->execute([$colab_id]);
            // Adiciona à equipa atual
            $pdo->prepare("INSERT INTO equipa_colaboradores (equipa_id, colaborador_id) VALUES (?, ?)")->execute([$equipa_id, $colab_id]);
        }
        return true;
    }

    public function alterarCoordenador($equipa_id, $coordenador_id) {
        $pdo = Database::getConnection();
        // Garante que o coordenador não é coordenador de outra equipa
        $pdo->prepare("UPDATE equipas SET coordenador_id = NULL WHERE coordenador_id = ? AND id <> ?")->execute([$coordenador_id, $equipa_id]);
        // Atualiza a equipa atual
        $stmt = $pdo->prepare("UPDATE equipas SET coordenador_id = ? WHERE id = ?");
        return $stmt->execute([$coordenador_id, $equipa_id]);
    }
}
?>
