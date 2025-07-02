<?php
require_once __DIR__ . '/../Database.php';

class DALFormacoes
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function listarFormacoesFuturas()
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM formacoes WHERE data_inicio >= CURDATE() AND ativo = 1 ORDER BY data_inicio ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
