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

    // Método para obter o nome do RH (colaborador) pelo user_id
    public function getRHNameByUserId($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT nome FROM colaboradores WHERE utilizador_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    public function getAlteracoesContratuais() {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT 
                c.nome as colaborador_nome, 
                lac.campo, 
                lac.valor_antigo, 
                lac.valor_novo, 
                lac.data_alteracao,
                lac.alterado_por_nome
            FROM logs_alteracoes_contratuais lac
            INNER JOIN colaboradores c ON lac.colaborador_id = c.id
            ORDER BY lac.data_alteracao DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>