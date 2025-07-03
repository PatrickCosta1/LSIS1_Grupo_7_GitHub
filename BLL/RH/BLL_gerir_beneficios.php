<?php
require_once __DIR__ . '/../../DAL/RH/DAL_gerir_beneficios.php';

class RHBeneficiosGerirManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_GerirBeneficios();
    }

    public function listarBeneficios() {
        return $this->dal->listarBeneficios();
    }

    public function adicionarBeneficio($dados) {
        return $this->dal->adicionarBeneficio($dados);
    }

    public function editarBeneficio($id, $dados) {
        return $this->dal->editarBeneficio($id, $dados);
    }

    public function removerBeneficio($id) {
        return $this->dal->removerBeneficio($id);
    }

    public function atualizarOrdem($ordens) {
        return $this->dal->atualizarOrdem($ordens);
    }
}
?>
