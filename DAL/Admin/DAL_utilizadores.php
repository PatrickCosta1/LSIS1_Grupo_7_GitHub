<?php
require_once __DIR__ . '/../Database.php';

class DAL_UtilizadoresAdmin {
    public function getAllUtilizadores() {
        $pdo = Database::getConnection();
        $sql = "SELECT u.id, c.nome, u.username, u.email, p.nome as perfil, u.ativo
                FROM utilizadores u
                LEFT JOIN colaboradores c ON u.id = c.utilizador_id
                LEFT JOIN perfis p ON u.perfil_id = p.id";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }
}
?>
