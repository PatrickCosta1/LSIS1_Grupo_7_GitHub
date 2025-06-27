<?php
require_once __DIR__ . '/../../DAL/Admin/DAL_alertas.php';

class AdminAlertasManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_AlertasAdmin();
    }
    public function getAllAlertas() {
        return $this->dal->getAllAlertas();
    }
}
?>