<?php
require_once __DIR__ . '/../Database.php';

class DAL_Equipas {
    public function getAllEquipas() {
        $pdo = Database::getConnection();
        $sql = "SELECT e.id, e.nome, c.nome as coordenador, 
                       (SELECT COUNT(*) FROM equipa_colaboradores ec WHERE ec.equipa_id = e.id) as num_colaboradores
                FROM equipas e
                LEFT JOIN colaboradores c ON e.coordenador_id = c.utilizador_id";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function addEquipa($nome, $coordenador_utilizador_id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO equipas (nome, coordenador_id) VALUES (?, ?)");
        return $stmt->execute([$nome, $coordenador_utilizador_id]);
    }

    public function getCoordenadores() {
        $pdo = Database::getConnection();
        $sql = "SELECT c.nome, u.id as utilizador_id
                FROM colaboradores c
                INNER JOIN utilizadores u ON c.utilizador_id = u.id
                INNER JOIN perfis p ON u.perfil_id = p.id
                WHERE LOWER(p.nome) = 'coordenador'";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }
}
?>