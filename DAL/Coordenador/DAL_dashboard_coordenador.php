<?php
require_once __DIR__ . '/../Database.php';

class DAL_DashboardCoordenador {
    public function getCoordenadorName($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT nome FROM colaboradores WHERE utilizador_id = ?");
        $stmt->execute([$userId]);
        $row = $stmt->fetch();
        return $row ? $row['nome'] : '';
    }

    public function getEquipasByCoordenador($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT e.id, e.nome FROM equipas e WHERE e.coordenador_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getColaboradoresByEquipa($equipaId) {
        $pdo = Database::getConnection();
        $sql = "SELECT c.id, c.nome, c.cargo, u.email
                FROM colaboradores c
                LEFT JOIN equipa_colaboradores ec ON c.id = ec.colaborador_id
                LEFT JOIN utilizadores u ON c.utilizador_id = u.id
                WHERE ec.equipa_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$equipaId]);
        return $stmt->fetchAll();
    }
}
?>