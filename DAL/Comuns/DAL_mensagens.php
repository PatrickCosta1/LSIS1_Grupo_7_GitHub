
<?php
require_once __DIR__ . '/../../DAL/Database.php';

class MensagensDAL {
    public function buscarMensagensPorDestinatario($userId) {
        $db = Database::getConnection();
        $sql = "SELECT m.*, u.username AS remetente_nome 
                FROM mensagens m
                JOIN utilizadores u ON m.remetente_id = u.id
                WHERE m.destinatario_id = ?
                ORDER BY m.data_envio DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function inserirMensagem($remetenteId, $destinatarioId, $assunto, $mensagem, $anexo = null) {
        $db = Database::getConnection();
        $sql = "INSERT INTO mensagens (remetente_id, destinatario_id, assunto, mensagem, anexo, data_envio, lida)
                VALUES (?, ?, ?, ?, ?, NOW(), 0)";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$remetenteId, $destinatarioId, $assunto, $mensagem, $anexo]);
    }

    public function marcarMensagemComoLida($mensagemId) {
        $db = Database::getConnection();
        $sql = "UPDATE mensagens SET lida = 1 WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$mensagemId]);
    }
}