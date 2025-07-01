<?php
require_once __DIR__ . '/../Database.php';

class DAL_FichaColaborador {
    public function getColaboradorByUserId($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM colaboradores WHERE utilizador_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function getColaboradorById($colabId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM colaboradores WHERE id = ?");
        $stmt->execute([$colabId]);
        return $stmt->fetch();
    }

    public function updateColaboradorByUserId($userId, $dados) {
        $pdo = Database::getConnection();
        $set = [];
        $params = [];
        foreach ($dados as $campo => $valor) {
            $set[] = "$campo = ?";
            $params[] = $valor;
        }
        $params[] = $userId;
        $sql = "UPDATE colaboradores SET " . implode(', ', $set) . " WHERE utilizador_id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function criarPedidoAlteracao($colaboradorId, $campo, $valorNovo, $valorAntigo) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO pedidos_alteracao_ficha (colaborador_id, campo, valor_novo, valor_antigo, estado, data_pedido) VALUES (?, ?, ?, ?, 'pendente', NOW())");
        return $stmt->execute([$colaboradorId, $campo, $valorNovo, $valorAntigo]);
    }

    public function aplicarAlteracao($colaboradorId, $campo, $valorNovo) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE colaboradores SET $campo = ? WHERE id = ?");
        return $stmt->execute([$valorNovo, $colaboradorId]);
    }

    public function listarPedidosPendentes() {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT p.*, c.nome as colaborador_nome FROM pedidos_alteracao_ficha p INNER JOIN colaboradores c ON p.colaborador_id = c.id WHERE p.estado = 'pendente' ORDER BY p.data_pedido DESC");
        return $stmt->fetchAll();
    }

    public function atualizarEstadoPedido($pedidoId, $estado) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE pedidos_alteracao_ficha SET estado = ?, data_resposta = NOW() WHERE id = ?");
        return $stmt->execute([$estado, $pedidoId]);
    }

    public function getPedidoById($pedidoId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM pedidos_alteracao_ficha WHERE id = ?");
        $stmt->execute([$pedidoId]);
        return $stmt->fetch();
    }
}