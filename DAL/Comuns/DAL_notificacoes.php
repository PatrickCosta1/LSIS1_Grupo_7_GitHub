<?php
require_once __DIR__ . '/../Database.php';

class DAL_Notificacoes {
    private $db;
    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function enviarNotificacao($remetenteId, $destinatarioId, $mensagem) {
        // Insere apenas o destinatário (utilizador_id), mensagem, data_envio e lida
        $stmt = $this->db->prepare("INSERT INTO notificacoes (utilizador_id, mensagem, data_envio, lida) VALUES (?, ?, NOW(), 0)");
        return $stmt->execute([$destinatarioId, $mensagem]);
    }

    public function getUtilizadorById($id) {
        $stmt = $this->db->prepare("SELECT id, username, email FROM utilizadores WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getUtilizadorIdByColaboradorId($colaboradorId) {
        $stmt = $this->db->prepare("SELECT utilizador_id FROM colaboradores WHERE id = ?");
        $stmt->execute([$colaboradorId]);
        $result = $stmt->fetch();
        return $result ? $result['utilizador_id'] : null;
    }

    public function getNotificacoesByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM notificacoes WHERE utilizador_id = ? ORDER BY data_envio DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function marcarComoLida($notificacaoId) {
        $stmt = $this->db->prepare("UPDATE notificacoes SET lida = 1 WHERE id = ?");
        return $stmt->execute([$notificacaoId]);
    }

    public function eliminarNotificacao($id) {
        $stmt = $this->db->prepare("DELETE FROM notificacoes WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function notificarRH($mensagem) {
        // Corrija aqui: utilize o campo correto para identificar RH
        // Exemplo: perfil_id = 4 (ajuste conforme o seu sistema)
        $stmt = $this->db->query("SELECT id FROM utilizadores WHERE perfil_id = 4");
        $rhs = $stmt->fetchAll();
        foreach ($rhs as $rh) {
            $this->enviarNotificacao(null, $rh['id'], $mensagem);
        }
        return true;
    }

    public function getRHUsers() {
        // Ajuste o valor de perfil_id conforme o seu sistema (exemplo: 4 para RH)
        $stmt = $this->db->query("SELECT id, username, email FROM utilizadores WHERE perfil_id = 4");
        return $stmt->fetchAll();
    }
}
?>