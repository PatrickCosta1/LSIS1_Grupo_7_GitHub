<?php
require_once __DIR__ . '/../../DAL/Colaborador/DAL_campos_personalizados.php';

class ColaboradorCamposPersonalizadosManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_CamposPersonalizadosColaborador();
    }
    public function getValoresByColaboradorId($colaborador_id) {
        return $this->dal->getValoresByColaboradorId($colaborador_id);
    }
    public function guardarValor($colaborador_id, $campo_id, $valor) {
        return $this->dal->guardarValor($colaborador_id, $campo_id, $valor);
    }
}
?>
