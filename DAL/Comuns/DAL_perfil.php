<?php
require_once __DIR__ . '/../Database.php';

class DAL_Perfil {
    public function getUserById($id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT u.*, c.nome FROM utilizadores u LEFT JOIN colaboradores c ON u.id = c.utilizador_id WHERE u.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateUserProfile($id, $nome, $email, $username) {
        $pdo = Database::getConnection();
        // Atualiza utilizadores e colaboradores
        $stmt1 = $pdo->prepare("UPDATE utilizadores SET email = ?, username = ? WHERE id = ?");
        $stmt2 = $pdo->prepare("UPDATE colaboradores SET nome = ? WHERE utilizador_id = ?");
        $ok1 = $stmt1->execute([$email, $username, $id]);
        $ok2 = $stmt2->execute([$nome, $id]);
        return $ok1 && $ok2;
    }
}
?>