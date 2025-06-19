<?php
require_once __DIR__ . '/../../DAL/RH/DAL_exportar.php';

class RHExportarManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_ExportarRH();
    }
    public function getAllColaboradores() {
        return $this->dal->getAllColaboradores();
    }
}
?>
