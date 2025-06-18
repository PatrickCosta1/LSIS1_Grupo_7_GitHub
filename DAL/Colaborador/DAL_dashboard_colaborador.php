<?php
require_once __DIR__ . '/../Database.php';

class DAL_DashboardColaborador {
    public function getColaboradorName($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT nome FROM colaboradores WHERE utilizador_id = ?");
        $stmt->execute([$userId]);
        $row = $stmt->fetch();
        return $row ? $row['nome'] : '';
    }
}
?>
