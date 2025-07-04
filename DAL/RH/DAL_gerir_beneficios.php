<?php
require_once __DIR__ . '/../../DAL/Database.php';

class DAL_GerirBeneficios {
    public function listarBeneficios() {
        $pdo = Database::getConnection();
        $sql = "SELECT * FROM beneficios ORDER BY ordem ASC, id ASC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function adicionarBeneficio($dados) {
        $pdo = Database::getConnection();
        $sql = "INSERT INTO beneficios (titulo, descricao, ordem) VALUES (:titulo, :descricao, :ordem)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':titulo' => $dados['titulo'],
            ':descricao' => $dados['descricao'],
            ':ordem' => $dados['ordem'] ?? 1
        ]);
    }

    public function editarBeneficio($id, $dados) {
        $pdo = Database::getConnection();
        $sql = "UPDATE beneficios SET titulo = :titulo, descricao = :descricao, ordem = :ordem WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':titulo' => $dados['titulo'],
            ':descricao' => $dados['descricao'],
            ':ordem' => $dados['ordem'] ?? 1,
            ':id' => $id
        ]);
    }

    public function removerBeneficio($id) {
        $pdo = Database::getConnection();
        $sql = "DELETE FROM beneficios WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function atualizarOrdem($ordens) {
        $pdo = Database::getConnection();
        $ok = true;
        foreach ($ordens as $id => $ordem) {
            $sql = "UPDATE beneficios SET ordem = :ordem WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $ok = $ok && $stmt->execute([':ordem' => $ordem, ':id' => $id]);
        }
        return $ok;
    }
}
?>
