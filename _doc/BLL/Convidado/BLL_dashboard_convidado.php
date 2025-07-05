<?php
require_once __DIR__ . '/../../DAL/Convidado/DAL_dashboard_convidado.php';

class ConvidadoDashboardManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_DashboardConvidado();
    }
    public function getConvidadoName($userId) {
        return $this->dal->getConvidadoName($userId);
    }
}
?>