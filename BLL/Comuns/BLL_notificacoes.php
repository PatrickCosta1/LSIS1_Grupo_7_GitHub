<?php
require_once __DIR__ . '/../../DAL/Comuns/DAL_notificacoes.php';

class NotificacoesManager {
    private $dal;
    
    public function __construct() {
        $this->dal = new DAL_Notificacoes();
    }
    
    public function criarNotificacao($utilizador_id, $mensagem, $tipo = null) {
        return $this->dal->criarNotificacao($utilizador_id, $mensagem, $tipo);
    }
    
    public function getNotificacoesPorUtilizador($utilizador_id) {
        return $this->dal->getNotificacoesPorUtilizador($utilizador_id);
    }
    
    public function marcarComoLida($notificacao_id, $utilizador_id) {
        return $this->dal->marcarComoLida($notificacao_id, $utilizador_id);
    }
    
    public function contarNaoLidas($utilizador_id) {
        return $this->dal->contarNaoLidas($utilizador_id);
    }
    
    public function marcarTodasComoLidas($utilizador_id) {
        return $this->dal->marcarTodasComoLidas($utilizador_id);
    }
    
    public function notificarRH($mensagem, $tipo = null) {
        // Buscar todos os utilizadores RH ativos
        $utilizadoresRH = $this->dal->getUtilizadoresRH();
        $sucessos = 0;
        
        foreach ($utilizadoresRH as $rh) {
            if ($this->criarNotificacao($rh['id'], $mensagem, $tipo)) {
                $sucessos++;
            }
        }
        
        return $sucessos;
    }
    
    public function notificarTodos($mensagem, $tipo = null) {
        // Buscar todos os utilizadores ativos
        $utilizadores = $this->dal->getTodosUtilizadores();
        $sucessos = 0;
        
        foreach ($utilizadores as $user) {
            if ($this->criarNotificacao($user['id'], $mensagem, $tipo)) {
                $sucessos++;
            }
        }
        
        return $sucessos;
    }
    
    public function notificarPorPerfil($perfil, $mensagem, $tipo = null) {
        // Buscar utilizadores por perfil
        $utilizadores = $this->dal->getUtilizadoresPorPerfil($perfil);
        $sucessos = 0;
        
        foreach ($utilizadores as $user) {
            if ($this->criarNotificacao($user['id'], $mensagem, $tipo)) {
                $sucessos++;
            }
        }
        
        return $sucessos;
    }
}
?>