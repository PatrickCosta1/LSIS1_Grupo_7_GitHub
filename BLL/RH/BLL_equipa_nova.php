<?php
require_once __DIR__ . '/../../DAL/RH/DAL_equipa_nova.php';

class EquipaNovaManager {
    private $dal;

    public function __construct() {
        $this->dal = new DAL_EquipaNova();
    }

    public function getCoordenadores() {
        return $this->dal->getCoordenadores();
    }

    public function getColaboradoresSemEquipa() {
        return $this->dal->getColaboradoresSemEquipa();
    }

    public function addEquipa($nome, $coordenador_id, $elementos = []) {
        return $this->dal->addEquipa($nome, $coordenador_id, $elementos);
    }
}