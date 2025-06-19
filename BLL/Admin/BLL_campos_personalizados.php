<?php
require_once __DIR__ . '/../../DAL/Admin/DAL_campos_personalizados.php';

class AdminCamposPersonalizadosManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_CamposPersonalizadosAdmin();
    }
    public function getAllCampos() {
        return $this->dal->getAllCampos();
    }
}
?>
