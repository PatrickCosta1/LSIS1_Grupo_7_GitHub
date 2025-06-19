<?php
require_once __DIR__ . '/Database.php';

class UserDataAccess {
    public function getUserByUsername($username) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
    // ...adicione outros métodos conforme necessário...
}
?>
