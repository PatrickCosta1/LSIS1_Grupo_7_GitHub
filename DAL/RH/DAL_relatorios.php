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
    public function getEquipasIndicadores() {
        $pdo = Database::getConnection();
        $sql = "SELECT e.nome, COUNT(ec.colaborador_id) as total_colaboradores
                FROM equipas e
                LEFT JOIN equipa_colaboradores ec ON e.id = ec.equipa_id
                GROUP BY e.id";
        return $pdo->query($sql)->fetchAll();
    }

    public function getAniversariosPorEquipa() {
        $pdo = Database::getConnection();
        $sql = "SELECT e.nome, COUNT(c.id) as aniversariantes
                FROM equipas e
                LEFT JOIN equipa_colaboradores ec ON e.id = ec.equipa_id
                LEFT JOIN colaboradores c ON ec.colaborador_id = c.id
                WHERE MONTH(c.data_nascimento) = MONTH(CURDATE())
                GROUP BY e.id";
        return $pdo->query($sql)->fetchAll();
    }
}
?>
