<?php
require_once __DIR__ . '/../../DAL/Comuns/DAL_notificacoes.php';

class NotificacoesManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_Notificacoes();
    }
    public function getNotificacoesByUserId($userId) {
        return $this->dal->getNotificacoesByUserId($userId);
    }
}
?>
