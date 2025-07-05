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

    public function addEquipa($nome, $responsavel_id, $elementos = [], $tipo = 'colaboradores') {
        return $this->dal->addEquipa($nome, $responsavel_id, $elementos, $tipo);
    }

    public function getResponsaveisPorTipo($tipo) {
        return $this->dal->getResponsaveisPorTipo($tipo);
    }

    public function getMembrosPorTipo($tipo) {
        return $this->dal->getMembrosPorTipo($tipo);
    }
}