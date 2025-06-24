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
    public function updateEquipa($id, $nome, $coordenador_utilizador_id) {
        return $this->dal->updateEquipa($id, $nome, $coordenador_utilizador_id);
    }

    // Adiciona um membro à equipa (retorna true/false)
    public function adicionarMembroEquipa($equipa_id, $colaborador_id) {
        return $this->dal->adicionarMembroEquipa($equipa_id, $colaborador_id);
    }

    // Remove um membro da equipa
    public function removerMembroEquipa($equipa_id, $colaborador_id) {
        return $this->dal->removerMembroEquipa($equipa_id, $colaborador_id);
    }

    // Retorna membros atuais da equipa com nome, perfil e função
    public function getMembrosEquipaDetalhado($equipa_id) {
        return $this->dal->getMembrosEquipaDetalhado($equipa_id);
    }

    // Retorna colaboradores que não estão em nenhuma equipa (com nome, perfil, função)
    public function getColaboradoresDisponiveisParaEquipa() {
        return $this->dal->getColaboradoresDisponiveisParaEquipa();
    }
}
?>
