<?php
require_once __DIR__ . '/../../DAL/Colaborador/DAL_ficha_colaborador.php';

class ColaboradorFichaManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_FichaColaborador();
    }
    public function getColaboradorByUserId($userId) {
        return $this->dal->getColaboradorByUserId($userId);
    }
    public function getColaboradorById($colabId) {
        return $this->dal->getColaboradorById($colabId);
    }
    public function updateColaboradorByUserId($userId, $dados, $perfil = null) {
        $colab = $this->dal->getColaboradorByUserId($userId);
        $colabId = $colab['id'] ?? null;
        if (!$colabId) return false;

        // Se for RH/Admin, altera diretamente
        if ($perfil === 'rh' || $perfil === 'admin') {
            // Campos contratuais que devem ser logados
            $camposContratuais = [
                'cargo', 'remuneracao', 'tipo_contrato', 'regime_horario', 
                'data_inicio_contrato', 'data_fim_contrato'
            ];
            
            // Se o $dados tiver 'id' (colaborador), atualizar por id, senão por utilizador_id
            if (isset($dados['id'])) {
                $colabId = $dados['id'];
                unset($dados['id']);
                
                // Buscar dados atuais do colaborador para comparar
                $colaboradorAtual = $this->dal->getColaboradorById($colabId);
                
                // Registar logs das alterações contratuais antes de atualizar
                foreach ($camposContratuais as $campo) {
                    if (isset($dados[$campo]) && $dados[$campo] != ($colaboradorAtual[$campo] ?? null)) {
                        $this->dal->registarLogAlteracaoContratual(
                            $colabId,
                            $campo,
                            $colaboradorAtual[$campo] ?? null,
                            $dados[$campo],
                            $userId
                        );
                    }
                }
                
                return $this->dal->updateColaboradorById($colabId, $dados);
            } else {
                // Registar logs das alterações contratuais antes de atualizar
                foreach ($camposContratuais as $campo) {
                    if (isset($dados[$campo]) && $dados[$campo] != ($colab[$campo] ?? null)) {
                        $this->dal->registarLogAlteracaoContratual(
                            $colabId,
                            $campo,
                            $colab[$campo] ?? null,
                            $dados[$campo],
                            $userId
                        );
                    }
                }
                
                return $this->dal->updateColaboradorByUserId($userId, $dados);
            }
        }

        // Caso contrário, cria pedidos de alteração para cada campo alterado
        $camposPermitidos = [
            'morada_fiscal', 'sexo', 'estado_civil', 'situacao_irs', 'dependentes', 'iban', 'habilitacoes', 'curso',
            'telemovel', 'matricula_viatura', 'nome_contacto_emergencia', 'grau_relacionamento',
            'contacto_emergencia', 'cartao_continente'
        ];
        $alterou = false;
        foreach ($camposPermitidos as $campo) {
            if (isset($dados[$campo]) && $dados[$campo] != ($colab[$campo] ?? null)) {
                $this->dal->criarPedidoAlteracao($colabId, $campo, $dados[$campo], $colab[$campo] ?? null);
                $alterou = true;
            }
        }
        return $alterou;
    }

    // Métodos para RH aprovar/recusar pedidos
    public function listarPedidosPendentes() {
        return $this->dal->listarPedidosPendentes();
    }
    public function getPedidoById($pedidoId) {
        return $this->dal->getPedidoById($pedidoId);
    }
    public function aprovarPedido($pedidoId) {
        $pedido = $this->dal->getPedidoById($pedidoId);
        if ($pedido && $pedido['estado'] === 'pendente') {
            $this->dal->aplicarAlteracao($pedido['colaborador_id'], $pedido['campo'], $pedido['valor_novo']);
            $this->dal->atualizarEstadoPedido($pedidoId, 'aprovado');
            return true;
        }
        return false;
    }
    public function recusarPedido($pedidoId) {
        return $this->dal->atualizarEstadoPedido($pedidoId, 'recusado');
    }

    // --- PEDIDOS DE FÉRIAS ---
    public function criarPedidoFerias($colaboradorId, $dataInicio, $dataFim) {
        return $this->dal->criarPedidoFerias($colaboradorId, $dataInicio, $dataFim);
    }
    public function listarPedidosFeriasPendentes() {
        return $this->dal->listarPedidosFeriasPendentes();
    }
    public function aprovarPedidoFerias($pedidoId) {
        return $this->dal->atualizarEstadoPedidoFerias($pedidoId, 'aceite');
    }
    public function recusarPedidoFerias($pedidoId) {
        return $this->dal->atualizarEstadoPedidoFerias($pedidoId, 'recusado');
    }
    public function getPedidoFeriasById($pedidoId) {
        return $this->dal->getPedidoFeriasById($pedidoId);
    }
    public function getPedidosFeriasPorColaborador($colaboradorId) {
        try {
            return $this->dal->getPedidosFeriasPorColaborador($colaboradorId);
        } catch (Exception $e) {
            // Se der erro, retornar array vazio
            error_log("Erro ao buscar pedidos de férias: " . $e->getMessage());
            return [];
        }
    }

    // --- PEDIDOS DE COMPROVATIVO ---
    public function criarPedidoComprovativo($colaboradorId, $tipoComprovativo, $comprovantivoAntigo, $comprovantivoNovo) {
        return $this->dal->criarPedidoComprovativo($colaboradorId, $tipoComprovativo, $comprovantivoAntigo, $comprovantivoNovo);
    }

    public function listarPedidosComprovantivosPendentes() {
        return $this->dal->listarPedidosComprovantivosPendentes();
    }

    public function aprovarPedidoComprovativo($pedidoId) {
        return $this->dal->aprovarPedidoComprovativo($pedidoId);
    }

    public function recusarPedidoComprovativo($pedidoId) {
        return $this->dal->recusarPedidoComprovativo($pedidoId);
    }

    public function getPedidoComprovantivoById($pedidoId) {
        return $this->dal->getPedidoComprovantivoById($pedidoId);
    }

    // Métodos para campos personalizados (ficha extra)
    public function getCamposPersonalizadosValores($colaborador_id) {
        return $this->dal->getCamposPersonalizadosValores($colaborador_id);
    }
    public function salvarCamposPersonalizadosValores($colaborador_id, $valores) {
        return $this->dal->salvarCamposPersonalizadosValores($colaborador_id, $valores);
    }
}
?>