<?php
require_once __DIR__ . '/../../DAL/RH/DAL_equipas.php';

class RHEquipasManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_Equipas();
    }
    public function getAllEquipas() {
        return $this->dal->getAllEquipas();
    }
    public function addEquipa($nome, $coordenador_utilizador_id) {
        return $this->dal->addEquipa($nome, $coordenador_utilizador_id);
    }
    public function getCoordenadores() {
        return $this->dal->getCoordenadores();
    }
}
?>