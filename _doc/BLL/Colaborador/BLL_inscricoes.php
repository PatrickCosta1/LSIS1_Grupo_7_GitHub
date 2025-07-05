<?php

class InscricoesManager
{
    private $dal;

    public function __construct()
    {
        require_once __DIR__ . '/../../DAL/Colaborador/DAL_inscricoes.php';
        $this->dal = new DALInscricoes();
    }

    public function inscrever($colaborador_id, $formacao_nome)
    {
        $this->dal->inscrever($colaborador_id, $formacao_nome);
    }
}