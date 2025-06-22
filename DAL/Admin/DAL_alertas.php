<?php
require_once __DIR__ . '/../Database.php';

class DAL_AlertasAdmin {
    public function getAllAlertas() {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM alertas");
        return $stmt->fetchAll();
    }
    public function addAlerta($tipo, $mensagem, $periodicidade_meses, $ativo, $criado_por) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO alertas (tipo, mensagem, periodicidade_meses, ativo, criado_por) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$tipo, $mensagem, $periodicidade_meses, $ativo, $criado_por]);
    }
}
?>
