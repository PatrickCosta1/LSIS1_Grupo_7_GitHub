<?php
require_once __DIR__ . '/../../DAL/Colaborador/DAL_formacoes.php';

class FormacoesManager
{
    private $dalFormacoes;

    public function __construct()
    {
        $this->dalFormacoes = new DALFormacoes();
    }

    public function listarFormacoesFuturas()
    {
        return $this->dalFormacoes->listarFormacoesFuturas();
    }

    public function inscrever($colaboradorId, $formacaoId) {
        $resultado = $this->dalFormacoes->inscreverColaborador($colaboradorId, $formacaoId);
        
        // Removida a notificação automática daqui - será enviada apenas no UI
        
        return $resultado;
    }

    public function jaInscrito($colaborador_id, $formacao_id)
    {
        return $this->dalFormacoes->jaInscrito($colaborador_id, $formacao_id);
    }
}
?>
                   
    
