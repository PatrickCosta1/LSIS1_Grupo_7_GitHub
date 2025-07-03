<?php
require_once __DIR__ . '/../Database.php';

class DAL_AlertaNovo {
    private $db;
    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function criarAlerta($tipo, $descricao, $periodicidade_meses, $ativo) {
        $stmt = $this->db->prepare("INSERT INTO alertas (tipo, descricao, periodicidade_meses, ativo) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$tipo, $descricao, $periodicidade_meses, $ativo]);
    }
}
?>
