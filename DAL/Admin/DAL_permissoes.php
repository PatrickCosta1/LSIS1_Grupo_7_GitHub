<?php
require_once __DIR__ . '/../Database.php';

class DAL_PermissoesAdmin {
    public function getAllPermissoes() {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("
            SELECT p.id, p.perfil_id, p.permissao, p.valor, pr.nome as perfil
            FROM permissoes p
            INNER JOIN perfis pr ON p.perfil_id = pr.id
            ORDER BY p.perfil_id, p.permissao
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function updatePermissao($id, $valor) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE permissoes SET valor = ? WHERE id = ?");
        return $stmt->execute([$valor, $id]);
    }
    
    public function getPermissoesPorPerfil($perfilId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT permissao, valor 
            FROM permissoes 
            WHERE perfil_id = ?
        ");
        $stmt->execute([$perfilId]);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[$row['permissao']] = $row['valor'];
        }
        return $result;
    }
    
    public function verificarPermissao($perfilId, $permissao) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT valor 
            FROM permissoes 
            WHERE perfil_id = ? AND permissao = ?
        ");
        $stmt->execute([$perfilId, $permissao]);
        return $stmt->fetchColumn() ? true : false;
    }
}
?>