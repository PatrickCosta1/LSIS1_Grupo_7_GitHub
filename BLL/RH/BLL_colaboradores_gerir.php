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
}
?>