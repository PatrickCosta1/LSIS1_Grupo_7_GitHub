<?php
require_once __DIR__ . '/../Database.php';

class DALEquipa {
    public function getEquipaById($id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM equipas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCoordenadoresDisponiveis($equipaId = null) {
        $pdo = Database::getConnection();
        // Seleciona todos os coordenadores ativos + o responsável atual da equipa (caso exista)
        $sql = "SELECT c.id, c.nome FROM colaboradores c
                INNER JOIN utilizadores u ON c.utilizador_id = u.id
                WHERE u.perfil_id = 3 AND u.ativo = 1
                " . ($equipaId ? "OR c.id = (SELECT responsavel_id FROM equipas WHERE id = ?)" : "");
        $stmt = $pdo->prepare($sql);
        if ($equipaId) {
            $stmt->execute([$equipaId]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retorna apenas colaboradores (perfil_id = 2) sem equipa, para adicionar numa equipa.
     */
    public function getColaboradoresSemEquipaSoColaboradores() {
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

    public function removerColaboradorDaEquipa($equipaId, $colabId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM equipa_colaboradores WHERE equipa_id = ? AND colaborador_id = ?");
        return $stmt->execute([$equipaId, $colabId]);
    }

    public function adicionarColaboradorAEquipa($equipaId, $colabId) {
        $pdo = Database::getConnection();
        // Evita duplicados
        $stmtCheck = $pdo->prepare("SELECT 1 FROM equipa_colaboradores WHERE equipa_id = ? AND colaborador_id = ?");
        $stmtCheck->execute([$equipaId, $colabId]);
        if ($stmtCheck->fetch()) return true;
        $stmt = $pdo->prepare("INSERT INTO equipa_colaboradores (equipa_id, colaborador_id) VALUES (?, ?)");
        return $stmt->execute([$equipaId, $colabId]);
    }

    public function atualizarNomeCoordenador($equipaId, $nome, $responsavelId) {
        $pdo = Database::getConnection();
        // Verifica se o NOVO responsável existe, é RH e está ativo
        $stmtCheck = $pdo->prepare("SELECT c.id FROM colaboradores c
            INNER JOIN utilizadores u ON c.utilizador_id = u.id
            WHERE c.id = ? AND u.perfil_id = 4 AND u.ativo = 1");
        $stmtCheck->execute([$responsavelId]);
        $responsavelExiste = $stmtCheck->fetchColumn();

        if (!$responsavelExiste) {
            return false;
        }

        $stmt = $pdo->prepare("UPDATE equipas SET nome = ?, responsavel_id = ? WHERE id = ?");
        return $stmt->execute([$nome, $responsavelId, $equipaId]);
    }

    public function removerEquipa($equipaId) {
        $pdo = Database::getConnection();
        $pdo->beginTransaction();
        try {
            // Antes de remover a equipa, defina o responsavel_id como NULL para evitar violação de FK
            $pdo->prepare("UPDATE equipas SET responsavel_id = NULL WHERE id = ?")->execute([$equipaId]);
            $pdo->prepare("DELETE FROM equipa_colaboradores WHERE equipa_id = ?")->execute([$equipaId]);
            $pdo->prepare("DELETE FROM equipas WHERE id = ?")->execute([$equipaId]);
            $pdo->commit();
            return true;
        } catch (\Exception $e) {
            $pdo->rollBack();
            return false;
        }
    }
}
?>
