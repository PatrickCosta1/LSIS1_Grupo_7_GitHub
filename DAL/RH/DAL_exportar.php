<?php
require_once __DIR__ . '/../Database.php';

class DAL_ExportarRH {
    public function getAllColaboradores() {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM colaboradores");
        return $stmt->fetchAll();
    }
}
?>