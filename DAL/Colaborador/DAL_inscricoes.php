<?php
require_once __DIR__ . '/../Database.php';

class DALInscricoes
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function inscrever($colaboradorId, $formacaoId)
    {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO inscricoes_formacao (colaborador_id, formacao_id, data_inscricao) VALUES (?, ?, NOW())"
            );
            return $stmt->execute([$colaboradorId, $formacaoId]);
        } catch (PDOException $e) {
            // Se já estiver inscrito, retorna false
            return false;
        }
    }

    public function jaInscrito($colaboradorId, $formacaoId)
    {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) FROM inscricoes_formacao WHERE colaborador_id = ? AND formacao_id = ?"
        );
        $stmt->execute([$colaboradorId, $formacaoId]);
        return $stmt->fetchColumn() > 0;
    }
}
?>