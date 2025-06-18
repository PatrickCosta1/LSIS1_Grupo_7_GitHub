<?php
require_once __DIR__ . '/../../DAL/Coordenador/DAL_dashboard_coordenador.php';

class CoordenadorDashboardManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_DashboardCoordenador();
    }
    public function getCoordenadorName($userId) {
        return $this->dal->getCoordenadorName($userId);
    }
}
?>
