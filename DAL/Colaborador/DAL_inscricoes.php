<?php
require_once __DIR__ . '/../Database.php';
class DALInscricoes
{
    private $conn;

    public function __construct()
    {
        require_once __DIR__ . '/../connection.php';
        $this->conn = getConnection(); // Função que retorna a ligação PDO à BD
    }

    public function inserirInscricao($colaborador_id, $formacao_nome)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO inscricoes_formacoes (colaborador_id, formacao_nome) VALUES (?, ?)"
        );
        return $stmt->execute([$colaborador_id, $formacao_nome]);
    }
}