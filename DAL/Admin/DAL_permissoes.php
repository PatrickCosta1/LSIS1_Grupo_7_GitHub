<?php
require_once __DIR__ . '/../Database.php';

class DAL_PermissoesAdmin {
    public function getAllPermissoes() {
        $pdo = Database::getConnection();
        $sql = "SELECT p.nome as perfil, GROUP_CONCAT(permissao SEPARATOR ', ') as permissoes
                FROM permissoes
                LEFT JOIN perfis p ON permissoes.perfil_id = p.id
                GROUP BY perfil_id";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }
}
?>
