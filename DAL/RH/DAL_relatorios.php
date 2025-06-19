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
}
?>
