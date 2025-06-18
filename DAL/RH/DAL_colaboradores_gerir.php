<?php
require_once __DIR__ . '/../Database.php';

class DAL_ColaboradoresGerir {
    public function getAllColaboradores() {
        $pdo = Database::getConnection();
        $sql = "SELECT c.id, c.nome, c.funcao, e.nome as equipa, u.email, u.ativo
                FROM colaboradores c
                LEFT JOIN equipa_colaboradores ec ON c.id = ec.colaborador_id
                LEFT JOIN equipas e ON ec.equipa_id = e.id
                LEFT JOIN utilizadores u ON c.utilizador_id = u.id";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }
}
?>
