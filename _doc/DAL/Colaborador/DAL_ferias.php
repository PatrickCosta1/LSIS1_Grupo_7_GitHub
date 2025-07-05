<?php
require_once __DIR__ . '/../Database.php';
class DALFerias
{
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function inserirPedidoFerias($colaborador_id, $data_inicio, $data_fim)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO pedidos_ferias (colaborador_id, data_inicio, data_fim) VALUES (?, ?, ?)"
        );
        return $stmt->execute([$colaborador_id, $data_inicio, $data_fim]);
    }
}