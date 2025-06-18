<?php
require_once __DIR__ . '/../../DAL/Admin/DAL_utilizadores.php';

class AdminUtilizadoresManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_UtilizadoresAdmin();
    }
    public function getAllUtilizadores() {
        return $this->dal->getAllUtilizadores();
    }
}
?>
