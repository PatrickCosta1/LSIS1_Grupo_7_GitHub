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

    public function getUtilizadorById($id) {
        $pdo = Database::getConnection();
        $sql = "SELECT u.id, c.nome, u.username, u.email, p.nome as perfil, u.ativo, u.perfil_id
                FROM utilizadores u
                LEFT JOIN colaboradores c ON u.id = c.utilizador_id
                LEFT JOIN perfis p ON u.perfil_id = p.id
                WHERE u.id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function addUtilizador($nome, $username, $email, $perfil_id, $ativo, $password, $tipo_rh = null) {
        $pdo = Database::getConnection();
        if ($tipo_rh !== null && $this->isRhPerfil($perfil_id)) {
            $stmt = $pdo->prepare("INSERT INTO utilizadores (username, email, perfil_id, ativo, password, tipo_rh) VALUES (?, ?, ?, ?, ?, ?)");
            $ok = $stmt->execute([$username, $email, $perfil_id, $ativo, $password, $tipo_rh]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO utilizadores (username, email, perfil_id, ativo, password) VALUES (?, ?, ?, ?, ?)");
            $ok = $stmt->execute([$username, $email, $perfil_id, $ativo, $password]);
        }
        if ($ok) {
            $id = $pdo->lastInsertId();
            // Cria colaborador automaticamente com o nome fornecido
            $stmt2 = $pdo->prepare("INSERT INTO colaboradores (utilizador_id, nome) VALUES (?, ?)");
            $stmt2->execute([$id, $nome]);
        }
        return $ok;
    }

    private function isRhPerfil($perfil_id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT nome FROM perfis WHERE id = ?");
        $stmt->execute([$perfil_id]);
        $perfil = $stmt->fetchColumn();
        return strtolower($perfil) === 'rh';
    }

    public function updateUtilizador($id, $nome, $username, $email, $perfil_id, $ativo) {
        $pdo = Database::getConnection();
        $stmt1 = $pdo->prepare("UPDATE utilizadores SET username = ?, email = ?, perfil_id = ?, ativo = ? WHERE id = ?");
        $stmt2 = $pdo->prepare("UPDATE colaboradores SET nome = ? WHERE utilizador_id = ?");
        $ok1 = $stmt1->execute([$username, $email, $perfil_id, $ativo, $id]);
        $ok2 = $stmt2->execute([$nome, $id]);
        return $ok1 && $ok2;
    }

    public function removeUtilizador($id) {
        $pdo = Database::getConnection();
        $stmt1 = $pdo->prepare("DELETE FROM colaboradores WHERE utilizador_id = ?");
        $stmt2 = $pdo->prepare("DELETE FROM utilizadores WHERE id = ?");
        $ok1 = $stmt1->execute([$id]);
        $ok2 = $stmt2->execute([$id]);
        return $ok1 && $ok2;
    }

    public function updatePassword($id, $password) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE utilizadores SET password = ? WHERE id = ?");
        return $stmt->execute([$password, $id]);
    }

    public function getPerfis() {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT id, nome FROM perfis");
        return $stmt->fetchAll();
    }
}
?>
