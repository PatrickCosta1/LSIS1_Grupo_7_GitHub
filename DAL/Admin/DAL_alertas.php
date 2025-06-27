<?php
require_once __DIR__ . '/../Database.php';

class DAL_AlertasAdmin {
    public function getAllAlertas() {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM alertas");
        return $stmt->fetchAll();
    }
}
?>