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
    public function getEquipaById($id) {
        return $this->dal->getEquipaById($id);
    }
    public function getColaboradoresByEquipa($equipa_id) {
        return $this->dal->getColaboradoresByEquipa($equipa_id);
    }
    public function atualizarColaboradoresEquipa($equipa_id, $colaboradores_ids) {
        return $this->dal->atualizarColaboradoresEquipa($equipa_id, $colaboradores_ids);
    }
    public function alterarCoordenador($equipa_id, $coordenador_id) {
        return $this->dal->alterarCoordenador($equipa_id, $coordenador_id);
    }
}
?>
