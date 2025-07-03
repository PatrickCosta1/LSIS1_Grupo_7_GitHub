<?php
require_once __DIR__ . '/../../DAL/RH/DAL_colaboradores_gerir.php';

class RHColaboradoresManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_ColaboradoresGerir();
    }

    public function getAllColaboradores($excludeUserId = null) {
        return $this->dal->getAllColaboradores($excludeUserId);
    }

    public function addColaborador($dados) {
        return $this->dal->addColaborador($dados);
    }

    public function getAllEquipas() {
        return $this->dal->getAllEquipas();
    }

    public function getAllPerfis() {
        return $this->dal->getAllPerfis();
    }

    public function getColaboradoresPorEquipa($equipaId) {
        return $this->dal->getColaboradoresPorEquipa($equipaId);
    }

    public function getColaboradoresPorPerfil($perfilId) {
        return $this->dal->getColaboradoresPorPerfil($perfilId);
    }
}
?>