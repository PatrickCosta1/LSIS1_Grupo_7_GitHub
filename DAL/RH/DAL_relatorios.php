<?php
require_once __DIR__ . '/../Database.php';

class DAL_RelatoriosRH {
    public function getIndicadoresGlobais() {
        $pdo = Database::getConnection();
        $totalColab = $pdo->query("SELECT COUNT(*) FROM colaboradores")->fetchColumn();
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
}
?>