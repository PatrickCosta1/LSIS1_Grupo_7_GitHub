<?php
require_once __DIR__ . '/../../DAL/RH/DAL_campos_personalizados.php';

class CamposPersonalizadosManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_CamposPersonalizados();
    }
    public function listarCampos() {
        return $this->dal->listarCampos();
    }
    public function adicionarCampo($nome, $tipo) {
        return $this->dal->adicionarCampo($nome, $tipo);
    }
    public function editarCampo($nome_antigo, $nome_novo, $tipo) {
        return $this->dal->editarCampo($nome_antigo, $nome_novo, $tipo);
    }
    public function removerCampo($nome) {
        return $this->dal->removerCampo($nome);
    }
}
