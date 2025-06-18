<?php
require_once __DIR__ . '/../../DAL/Admin/DAL_dashboard_admin.php';

class AdminDashboardManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_DashboardAdmin();
    }
    public function getAdminName($userId) {
        return $this->dal->getAdminName($userId);
    }
}
?>
