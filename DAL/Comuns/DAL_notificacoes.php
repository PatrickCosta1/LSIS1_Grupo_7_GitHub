<?php
require_once __DIR__ . '/../Database.php';

class DAL_Notificacoes {
    public function getNotificacoesByUserId($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM notificacoes WHERE utilizador_id = ? ORDER BY data_envio DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}
?>
