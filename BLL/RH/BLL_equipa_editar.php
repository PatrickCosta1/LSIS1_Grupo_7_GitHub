<?php
require_once __DIR__ . '/../../DAL/RH/DAL_equipa_editar.php';

class RHEquipaEditarManager {
    private $dal;

    public function __construct() {
        $this->dal = new DALEquipa();
    }

    public function getEquipaById($id) {
        return $this->dal->getEquipaById($id);
    }

    public function getCoordenadoresDisponiveis($equipaId = null) {
        return $this->dal->getCoordenadoresDisponiveis($equipaId);
    }

    public function getColaboradoresSemEquipaSoColaboradores() {
        return $this->dal->getColaboradoresSemEquipaSoColaboradores();
    }

    public function getColaboradoresDaEquipa($equipaId) {
        return $this->dal->getColaboradoresDaEquipa($equipaId);
    }

    public function removerColaboradorDaEquipa($equipaId, $colabId) {
        return $this->dal->removerColaboradorDaEquipa($equipaId, $colabId);
    }

    public function adicionarColaboradorAEquipa($equipaId, $colabId) {
        return $this->dal->adicionarColaboradorAEquipa($equipaId, $colabId);
    }

    public function atualizarNomeCoordenador($equipaId, $nome, $coordenadorId) {
        // $coordenadorId deve ser o ID do colaborador
        return $this->dal->atualizarNomeCoordenador($equipaId, $nome, $coordenadorId);
    }

    public function removerEquipa($equipaId) {
        return $this->dal->removerEquipa($equipaId);
    }
}
?>
