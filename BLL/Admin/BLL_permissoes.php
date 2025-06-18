<?php
require_once __DIR__ . '/../../DAL/Admin/DAL_permissoes.php';

class AdminPermissoesManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_PermissoesAdmin();
    }
    public function getAllPermissoes() {
        return $this->dal->getAllPermissoes();
    }
}
?>
