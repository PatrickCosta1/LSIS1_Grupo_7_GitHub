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
            return $this->dal->updateColaboradorByUserId($userId, $dados);
        }

        // Caso contrário, cria pedidos de alteração para cada campo alterado
        $camposPermitidos = [
            'morada_fiscal', 'sexo', 'situacao_irs', 'dependentes', 'iban', 'habilitacoes', 'curso',
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
}
?>