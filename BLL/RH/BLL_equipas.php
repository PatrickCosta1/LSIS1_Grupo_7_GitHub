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
    public function addEquipa($nome, $coordenador_colaborador_id, $elementos = []) {
        return $this->dal->addEquipa($nome, $coordenador_colaborador_id, $elementos);
    }
    public function getCoordenadores() {
        return $this->dal->getCoordenadores();
    }
    public function getColaboradores() {
        return $this->dal->getColaboradores();
    }
    public function getEquipaById($id) {
        return $this->dal->getEquipaById($id);
    }
    public function getCoordenadoresDisponiveis($equipaId = null) {
        return $this->dal->getCoordenadoresDisponiveis($equipaId);
    }
    public function getColaboradoresDaEquipa($equipaId) {
        return $this->dal->getColaboradoresDaEquipa($equipaId);
    }
}
?>