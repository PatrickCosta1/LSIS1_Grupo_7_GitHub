<?php
require_once __DIR__ . '/../../DAL/Admin/DAL_campos_personalizados.php';

class AdminCamposPersonalizadosManager {
    private $dal;
    public function __construct() {
        $this->dal = new AdminCamposPersonalizadosManagerDAL();
    }
    public function getAllCampos() {
        return $this->dal->getAllCampos();
    }
    public function addCampo($nome, $tipo) {
        return $this->dal->addCampo($nome, $tipo);
    }
    public function updateCampo($id, $nome, $tipo) {
        return $this->dal->updateCampo($id, $nome, $tipo);
    }
    public function removeCampo($id) {
        return $this->dal->removeCampo($id);
    }
    public function getValoresByColaborador($colaborador_id) {
        return $this->dal->getValoresByColaborador($colaborador_id);
    }
    public function setValorCampo($colaborador_id, $campo_id, $valor) {
        return $this->dal->setValorCampo($colaborador_id, $campo_id, $valor);
    }
}
?>
