<?php
require_once __DIR__ . '/../../DAL/RH/DAL_gerir_formacoes.php';

class RHFormacoesGerirManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_GerirFormacoes();
    }

    public function listarFormacoes() {
        return $this->dal->listarFormacoes();
    }

    public function adicionarFormacao($dados) {
        return $this->dal->adicionarFormacao($dados);
    }

    public function editarFormacao($id, $dados) {
        return $this->dal->editarFormacao($id, $dados);
    }

    public function removerFormacao($id) {
        return $this->dal->removerFormacao($id);
    }
}
?>
