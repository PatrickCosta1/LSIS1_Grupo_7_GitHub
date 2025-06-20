<?php
require_once __DIR__ . '/../Database.php';

class DAL_FichaColaborador {
    public function getColaboradorByUserId($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM colaboradores WHERE utilizador_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function updateColaboradorByUserId($userId, $dados, $comprovativo = null) {
        $pdo = Database::getConnection();
        $campos = [
            'nome' => $dados['nome'] ?? null,
            'morada' => $dados['morada'] ?? null,
            'estado_civil' => $dados['estado_civil'] ?? null,
            'habilitacoes' => $dados['habilitacoes'] ?? null,
            'contacto_emergencia' => $dados['contacto_emergencia'] ?? null,
            'matricula_viatura' => $dados['matricula_viatura'] ?? null,
            'data_nascimento' => $dados['data_nascimento'] ?? null,
            'funcao' => $dados['funcao'] ?? null,
            'geografia' => $dados['geografia'] ?? null,
            'nivel_hierarquico' => $dados['nivel_hierarquico'] ?? null,
            'remuneracao' => $dados['remuneracao'] ?? null,
            'genero' => $dados['genero'] ?? null
        ];
        if ($comprovativo) {
            $campos['comprovativo_estado_civil'] = $comprovativo;
        }
        $set = [];
        $params = [];
        foreach ($campos as $campo => $valor) {
            if ($valor !== null) {
                $set[] = "$campo = ?";
                $params[] = $valor;
            }
        }
        $params[] = $userId;
        $sql = "UPDATE colaboradores SET " . implode(', ', $set) . " WHERE utilizador_id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function getColaboradorById($colabId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM colaboradores WHERE id = ?");
        $stmt->execute([$colabId]);
        return $stmt->fetch();
    }
}
?>
