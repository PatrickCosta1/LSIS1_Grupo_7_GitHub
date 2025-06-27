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
        // Atualiza utilizadores
        $stmt1 = $pdo->prepare("UPDATE utilizadores SET email = ?, username = ? WHERE id = ?");
        $ok1 = $stmt1->execute([$email, $username, $id]);

        // Atualiza colaboradores
        $stmt2 = $pdo->prepare("UPDATE colaboradores SET nome = ? WHERE utilizador_id = ?");
        $stmt2->execute([$nome, $id]);
        // Se não atualizou nenhuma linha, faz insert
        if ($stmt2->rowCount() === 0) {
            $stmt3 = $pdo->prepare("INSERT INTO colaboradores (utilizador_id, nome) VALUES (?, ?)");
            $stmt3->execute([$id, $nome]);
        }
        return $ok1;
    }
}
?>