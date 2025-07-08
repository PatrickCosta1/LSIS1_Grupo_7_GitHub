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

    public function inscrever($colaboradorId, $formacaoId) {
        $resultado = $this->dalInscricoes->inscrever($colaboradorId, $formacaoId);
        
        if ($resultado) {
            // Buscar dados da formação para notificação detalhada
            $formacao = $this->dalFormacoes->getFormacaoById($formacaoId);
            
            if ($formacao) {
                require_once __DIR__ . '/../Comuns/BLL_notificacoes.php';
                $notBLL = new NotificacoesManager();
                
                // Obter utilizador_id do colaborador
                $colaborador = $this->dalFormacoes->getColaboradorById($colaboradorId);
                if ($colaborador && isset($colaborador['utilizador_id'])) {
                    $dataInicio = date('d/m/Y', strtotime($formacao['data_inicio']));
                    $dataFim = date('d/m/Y', strtotime($formacao['data_fim']));
                    
                    $mensagem = "Inscrição processada com sucesso na formação '{$formacao['nome']}'. " .
                               "Cronograma: {$dataInicio} a {$dataFim}. " .
                               "Código de referência: FM-" . str_pad($formacaoId, 4, '0', STR_PAD_LEFT) . ". " .
                               "O Departamento de Recursos Humanos enviará as instruções complementares e cronograma detalhado por email.";
                    
                    $notBLL->enviarNotificacao(null, $colaborador['utilizador_id'], $mensagem);
                }
            }
        }
        
        return $resultado;
    }

    public function jaInscrito($colaborador_id, $formacao_id)
    {
        return $this->dalInscricoes->jaInscrito($colaborador_id, $formacao_id);
    }
}
?>