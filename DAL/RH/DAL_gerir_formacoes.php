<?php
require_once __DIR__ . '/../../DAL/Database.php';

class DAL_GerirFormacoes {
    public function listarFormacoes() {
        $pdo = Database::getConnection();
        $sql = "SELECT * FROM formacoes ORDER BY data_inicio DESC, nome ASC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function adicionarFormacao($dados) {
        $pdo = Database::getConnection();
        $sql = "INSERT INTO formacoes (nome, descricao, data_inicio, data_fim, horario_semanal)
                VALUES (:nome, :descricao, :data_inicio, :data_fim, :horario_semanal)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':nome' => $dados['nome'],
            ':descricao' => $dados['descricao'],
            ':data_inicio' => $dados['data_inicio'],
            ':data_fim' => $dados['data_fim'],
            ':horario_semanal' => $dados['horario_semanal']
        ]);
    }

    public function editarFormacao($id, $dados) {
        $pdo = Database::getConnection();
        $sql = "UPDATE formacoes SET nome = :nome, descricao = :descricao, data_inicio = :data_inicio, data_fim = :data_fim, horario_semanal = :horario_semanal
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':nome' => $dados['nome'],
            ':descricao' => $dados['descricao'],
            ':data_inicio' => $dados['data_inicio'],
            ':data_fim' => $dados['data_fim'],
            ':horario_semanal' => $dados['horario_semanal'],
            ':id' => $id
        ]);
    }

    public function removerFormacao($id) {
        $pdo = Database::getConnection();
        $sql = "DELETE FROM formacoes WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
?>
