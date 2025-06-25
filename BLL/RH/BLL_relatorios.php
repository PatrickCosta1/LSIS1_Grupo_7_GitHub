<?php
require_once __DIR__ . '/../../DAL/RH/DAL_relatorios.php';

class RHRelatoriosManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_RelatoriosRH();
    }
    public function getIndicadoresGlobais() {
        return $this->dal->getIndicadoresGlobais();
    }
    public function getEquipasIndicadores() {
        return $this->dal->getEquipasIndicadores();
    }
    public function getAniversariosPorEquipa() {
        return $this->dal->getAniversariosPorEquipa();
    }
}
?>
