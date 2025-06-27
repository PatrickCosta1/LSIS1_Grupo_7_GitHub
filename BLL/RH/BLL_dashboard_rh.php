<?php
require_once __DIR__ . '/../../DAL/RH/DAL_dashboard_rh.php';

class RHDashboardManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_DashboardRH();
    }
    public function getRHName($userId) {
        return $this->dal->getRHName($userId);
    }
}
?>