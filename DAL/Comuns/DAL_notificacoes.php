<?php
require_once __DIR__ . '/../Database.php';

class DAL_Notificacoes {
    public function getNotificacoesByUserId($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM notificacoes WHERE utilizador_id = ? ORDER BY data_envio DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function marcarComoLida($notificacaoId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE notificacoes SET lida = 1 WHERE id = ?");
        return $stmt->execute([$notificacaoId]);
    }

    // Novo: Notificar todos RH
    public function notificarRH($mensagem) {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT id FROM utilizadores WHERE perfil_id = 4 AND ativo = 1");
        $rhs = $stmt->fetchAll();
        foreach ($rhs as $rh) {
            $stmt2 = $pdo->prepare("INSERT INTO notificacoes (utilizador_id, mensagem, lida, data_envio) VALUES (?, ?, 0, NOW())");
            $stmt2->execute([$rh['id'], $mensagem]);
        }
    }
}
?>