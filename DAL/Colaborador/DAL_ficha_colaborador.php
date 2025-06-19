<?php
require_once __DIR__ . '/../Database.php';

class DAL_FichaColaborador {
    public function getColaboradorByUserId($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM colaboradores WHERE utilizador_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function updateColaboradorByUserId($userId, $dados) {
        $pdo = Database::getConnection();
        $sql = "UPDATE colaboradores SET 
            nome = ?, morada = ?, estado_civil = ?, habilitacoes = ?, contacto_emergencia = ?, matricula_viatura = ?, data_nascimento = ?, funcao = ?, geografia = ?, nivel_hierarquico = ?, remuneracao = ?, genero = ?
            WHERE utilizador_id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $dados['nome'],
            $dados['morada'],
            $dados['estado_civil'],
            $dados['habilitacoes'],
            $dados['contacto_emergencia'],
            $dados['matricula_viatura'],
            $dados['data_nascimento'],
            $dados['funcao'],
            $dados['geografia'],
            $dados['nivel_hierarquico'],
            $dados['remuneracao'],
            $dados['genero'],
            $userId
        ]);
    }

    public function getColaboradorById($colabId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM colaboradores WHERE id = ?");
        $stmt->execute([$colabId]);
        return $stmt->fetch();
    }
}
?>
