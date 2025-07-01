<?php
require_once __DIR__ . '/../../DAL/Admin/DAL_alerta_novo.php';

class AlertaNovoManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_AlertaNovo();
    }

    public function criarAlerta($tipo, $descricao, $periodicidade_meses, $ativo) {
        return $this->dal->criarAlerta($tipo, $descricao, $periodicidade_meses, $ativo);
    }
}
?>
