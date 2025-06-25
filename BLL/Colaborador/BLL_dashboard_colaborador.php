<?php
require_once __DIR__ . '/../../DAL/Colaborador/DAL_dashboard_colaborador.php';

class ColaboradorDashboardManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_DashboardColaborador();
    }
    public function getColaboradorName($userId) {
        return $this->dal->getColaboradorName($userId);
    }
}
?>