<?php
require_once __DIR__ . '/../Database.php';

class DAL_AlertasAdmin {
    public function getAllAlertas() {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM alertas");
        return $stmt->fetchAll();
    }

    public function criarAlerta($tipo, $descricao, $periodicidade_meses, $ativo, $destinatario) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO alertas (tipo, descricao, periodicidade_meses, ativo, destinatario) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$tipo, $descricao, $periodicidade_meses, $ativo, $destinatario]);
    }

    public function getAlertasParaNotificar() {
        $pdo = Database::getConnection();
        // Busca alertas ativos
        $stmt = $pdo->query("SELECT * FROM alertas WHERE ativo = 1");
        return $stmt->fetchAll();
    }
}
?>