<?php

class DALFerias
{
    private $conn;

    public function __construct()
    {
        require_once __DIR__ . '/../connection.php';
        $this->conn = getConnection(); // Função que retorna a ligação PDO à BD
    }

    public function inserirPedidoFerias($colaborador_id, $data_inicio, $data_fim)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO pedidos_ferias (colaborador_id, data_inicio, data_fim) VALUES (?, ?, ?)"
        );
        return $stmt->execute([$colaborador_id, $data_inicio, $data_fim]);
    }
}