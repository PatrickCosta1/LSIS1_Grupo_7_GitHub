<?php
require_once __DIR__ . '/../Database.php';

class DAL_CamposPersonalizadosAdmin {
    public function getAllCampos() {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM campos_personalizados");
        return $stmt->fetchAll();
    }
}
?>