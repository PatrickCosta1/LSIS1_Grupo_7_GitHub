<?php
require_once __DIR__ . '/../Database.php';

class DAL_FichaColaborador {
    public function getColaboradorByUserId($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM colaboradores WHERE utilizador_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function getColaboradorById($colabId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM colaboradores WHERE id = ?");
        $stmt->execute([$colabId]);
        return $stmt->fetch();
    }

    public function updateColaboradorByUserId($userId, $dados) {
        $pdo = Database::getConnection();
        $set = [];
        $params = [];
        foreach ($dados as $campo => $valor) {
            $set[] = "$campo = ?";
            $params[] = $valor;
        }
        $params[] = $userId;
        $sql = "UPDATE colaboradores SET " . implode(', ', $set) . " WHERE utilizador_id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    }
}
