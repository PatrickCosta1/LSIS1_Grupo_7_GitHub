<?php
require_once __DIR__ . '/../../DAL/Colaborador/DAL_formacoes.php';
require_once __DIR__ . '/../../DAL/Colaborador/DAL_inscricoes.php';

class FormacoesManager
{
    private $dalFormacoes;
    private $dalInscricoes;

    public function __construct()
    {
        $this->dalFormacoes = new DALFormacoes();
        $this->dalInscricoes = new DALInscricoes();
    }

    public function listarFormacoesFuturas()
    {
        return $this->dalFormacoes->listarFormacoesFuturas();
    }

    public function inscrever($colaborador_id, $formacao_id)
    {
        if ($this->dalInscricoes->jaInscrito($colaborador_id, $formacao_id)) {
            return false;
        }
        return $this->dalInscricoes->inserirInscricao($colaborador_id, $formacao_id);
    }

    public function jaInscrito($colaborador_id, $formacao_id)
    {
        return $this->dalInscricoes->jaInscrito($colaborador_id, $formacao_id);
    }
}
