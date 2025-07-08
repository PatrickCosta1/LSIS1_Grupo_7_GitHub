<?php
require_once __DIR__ . '/../Database.php';

class DAL_Notificacoes {
    
    public function criarNotificacao($utilizador_id, $mensagem, $tipo = null) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO notificacoes (utilizador_id, mensagem, tipo, data_envio, lida) 
            VALUES (?, ?, ?, NOW(), 0)
        ");
        return $stmt->execute([$utilizador_id, $mensagem, $tipo]);
    }
    
    public function getNotificacoesPorUtilizador($utilizador_id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT id, mensagem, tipo, data_envio, lida 
            FROM notificacoes 
            WHERE utilizador_id = ? 
            ORDER BY data_envio DESC 
            LIMIT 50
        ");
        $stmt->execute([$utilizador_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function marcarComoLida($notificacao_id, $utilizador_id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            UPDATE notificacoes 
            SET lida = 1 
            WHERE id = ? AND utilizador_id = ?
        ");
        return $stmt->execute([$notificacao_id, $utilizador_id]);
    }
    
    public function contarNaoLidas($utilizador_id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT COUNT(*) 
            FROM notificacoes 
            WHERE utilizador_id = ? AND lida = 0
        ");
        $stmt->execute([$utilizador_id]);
        return $stmt->fetchColumn();
    }
    
    public function getUtilizadoresRH() {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT u.id, u.username, u.email 
            FROM utilizadores u 
            INNER JOIN perfis p ON u.perfil_id = p.id 
            WHERE p.nome = 'rh' AND u.ativo = 1
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTodosUtilizadores() {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT u.id, u.username, u.email 
            FROM utilizadores u 
            WHERE u.ativo = 1
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getUtilizadoresPorPerfil($perfil) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT u.id, u.username, u.email 
            FROM utilizadores u 
            INNER JOIN perfis p ON u.perfil_id = p.id 
            WHERE p.nome = ? AND u.ativo = 1
        ");
        $stmt->execute([$perfil]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function marcarTodasComoLidas($utilizador_id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            UPDATE notificacoes 
            SET lida = 1 
            WHERE utilizador_id = ? AND lida = 0
        ");
        return $stmt->execute([$utilizador_id]);
    }
    
    public function removerNotificacao($notificacao_id, $utilizador_id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            DELETE FROM notificacoes 
            WHERE id = ? AND utilizador_id = ?
        ");
        return $stmt->execute([$notificacao_id, $utilizador_id]);
    }
    
    public function getEmailByUtilizadorId($utilizador_id) {
        $db = \Database::getConnection();
        $stmt = $db->prepare("SELECT email FROM utilizadores WHERE id = ?");
        $stmt->execute([$utilizador_id]);
        $row = $stmt->fetch();
        return $row ? $row['email'] : null;
    }
}
?>