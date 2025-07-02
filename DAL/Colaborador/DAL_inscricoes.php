<?php
require_once __DIR__ . '/../Database.php';
class DALInscricoes
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function inserirInscricao($colaborador_id, $formacao_id)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO inscricao_formacoes (colaborador_id, formacao_id, data_inscricao) VALUES (?, ?, NOW())"
        );
        return $stmt->execute([$colaborador_id, $formacao_id]);
    }

    public function jaInscrito($colaborador_id, $formacao_id)
    {
        $stmt = $this->conn->prepare(
            "SELECT id FROM inscricao_formacoes WHERE colaborador_id = ? AND formacao_id = ?"
        );
        $stmt->execute([$colaborador_id, $formacao_id]);
        return $stmt->fetch() ? true : false;
    }
}