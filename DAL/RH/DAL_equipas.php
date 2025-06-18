<?php
require_once __DIR__ . '/../Database.php';

class DAL_Equipas {
    public function getAllEquipas() {
        $pdo = Database::getConnection();
        $sql = "SELECT e.id, e.nome, u.username as coordenador, 
                       (SELECT COUNT(*) FROM equipa_colaboradores ec WHERE ec.equipa_id = e.id) as num_colaboradores
                FROM equipas e
                LEFT JOIN utilizadores u ON e.coordenador_id = u.id";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }
}
?>
