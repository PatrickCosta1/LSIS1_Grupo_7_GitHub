<?php
require_once __DIR__ . '/../Database.php';

class DAL_Perfil {
    public function getUserById($id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT u.*, c.nome FROM utilizadores u LEFT JOIN colaboradores c ON u.id = c.utilizador_id WHERE u.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
?>
