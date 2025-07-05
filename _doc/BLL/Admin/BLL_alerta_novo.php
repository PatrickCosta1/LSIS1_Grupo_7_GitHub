<?php
require_once __DIR__ . '/../../DAL/Admin/DAL_alertas.php';

class AlertaNovoManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_AlertasAdmin();
    }

    public function criarAlerta($tipo, $descricao, $periodicidade_meses, $ativo, $destinatario) {
        return $this->dal->criarAlerta($tipo, $descricao, $periodicidade_meses, $ativo, $destinatario);
    }
}
?>
