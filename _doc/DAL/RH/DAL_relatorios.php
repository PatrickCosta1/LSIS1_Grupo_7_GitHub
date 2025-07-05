<?php
require_once __DIR__ . '/../Database.php';

class DAL_RelatoriosRH {
    public function getIndicadoresGlobais() {
        $pdo = Database::getConnection();
        // Corrigido: total de colaboradores agora é o total de utilizadores
        $totalColab = $pdo->query("SELECT COUNT(*) FROM utilizadores")->fetchColumn();
        $ativos = $pdo->query("SELECT COUNT(*) FROM utilizadores WHERE ativo = 1")->fetchColumn();
        $inativos = $pdo->query("SELECT COUNT(*) FROM utilizadores WHERE ativo = 0")->fetchColumn();
        $totalEquipas = $pdo->query("SELECT COUNT(*) FROM equipas")->fetchColumn();
        return [
            'total_colaboradores' => $totalColab,
            'ativos' => $ativos,
            'inativos' => $inativos,
            'total_equipas' => $totalEquipas
        ];
    }

    public function getEquipas() {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT id, nome FROM equipas");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAniversariosPorEquipa($equipaId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT c.id, c.nome, c.data_nascimento
            FROM equipa_colaboradores ec
            JOIN colaboradores c ON ec.colaborador_id = c.id
            WHERE ec.equipa_id = ?
            ORDER BY MONTH(c.data_nascimento), DAY(c.data_nascimento)
        ");
        $stmt->execute([$equipaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Novo método para obter o nome do RH (colaborador) pelo user_id
    public function getNomeColaboradorByUserId($userId) {
        $pdo = Database::getConnection();
        // Primeiro, obter o colaborador_id associado ao utilizador
        $stmt = $pdo->prepare("SELECT colaborador_id FROM utilizadores WHERE id = ?");
        $stmt->execute([$userId]);
        $colabId = $stmt->fetchColumn();
        if (!$colabId) return null;
        // Agora, obter o nome do colaborador
        $stmt = $pdo->prepare("SELECT nome FROM colaboradores WHERE id = ?");
        $stmt->execute([$colabId]);
        return $stmt->fetchColumn();
    }
}
?>