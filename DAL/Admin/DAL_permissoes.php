<?php
require_once __DIR__ . '/../Database.php';

class DAL_PermissoesAdmin {
    public function getAllPermissoes() {
        $pdo = Database::getConnection();
        $sql = "SELECT p.id, pf.nome as perfil, p.perfil_id, p.permissao, p.valor
                FROM permissoes p
                LEFT JOIN perfis pf ON p.perfil_id = pf.id
                ORDER BY p.perfil_id, p.permissao";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function updatePermissao($id, $valor) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE permissoes SET valor = ? WHERE id = ?");
        return $stmt->execute([$valor, $id]);
    }
}
?>