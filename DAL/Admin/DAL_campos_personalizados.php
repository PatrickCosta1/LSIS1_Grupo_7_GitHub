<?php
require_once __DIR__ . '/../Database.php';

class DAL_CamposPersonalizadosAdmin {
    public function getAllCampos() {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM campos_personalizados");
        return $stmt->fetchAll();
    }

    public function addCampo($nome, $tipo, $obrigatorio, $ordem) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO campos_personalizados (nome, tipo, obrigatorio, ordem) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nome, $tipo, $obrigatorio, $ordem]);
    }
}
?>
