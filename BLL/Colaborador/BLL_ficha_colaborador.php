<?php
require_once __DIR__ . '/../../DAL/Colaborador/DAL_ficha_colaborador.php';

class ColaboradorFichaManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_FichaColaborador();
    }
    public function getColaboradorByUserId($userId) {
        return $this->dal->getColaboradorByUserId($userId);
    }
    public function updateColaboradorByUserId($userId, $dados) {
        // Aceita comprovativo_estado_civil se existir
        $comprovativo = isset($dados['comprovativo_estado_civil']) ? $dados['comprovativo_estado_civil'] : null;
        return $this->dal->updateColaboradorByUserId($userId, $dados, $comprovativo);
    }
    public function getColaboradorById($colabId) {
        return $this->dal->getColaboradorById($colabId);
    }
}
?>