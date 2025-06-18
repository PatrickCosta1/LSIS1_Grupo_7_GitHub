<?php
require_once __DIR__ . '/../Database.php';

class DAL_Login {
    public function getUserByUsername($username) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
    public function getProfileName($perfil_id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT nome FROM perfis WHERE id = ?");
        $stmt->execute([$perfil_id]);
        $row = $stmt->fetch();
        return $row ? $row['nome'] : '';
    }
    public function getColaboradorName($utilizador_id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT nome FROM colaboradores WHERE utilizador_id = ?");
        $stmt->execute([$utilizador_id]);
        $row = $stmt->fetch();
        return $row ? $row['nome'] : '';
    }
}
?>
