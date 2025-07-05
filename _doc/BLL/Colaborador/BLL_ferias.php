<?php

class FeriasManager
{
    private $dal;

    public function __construct()
    {
        require_once __DIR__ . '/../../DAL/Colaborador/DAL_ferias.php';
        $this->dal = new DALFerias();
    }

    public function pedirFerias($colaborador_id, $data_inicio, $data_fim)
    {
        return $this->dal->inserirPedidoFerias($colaborador_id, $data_inicio, $data_fim);
    }
}