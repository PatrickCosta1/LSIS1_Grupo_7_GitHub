<?php
require_once __DIR__ . '/../Database.php';

class DAL_OnboardingConvidado {
    public function getConvidadoByUserId($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM colaboradores WHERE utilizador_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }
}
?>