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
            'morada_fiscal' => $dados['morada_fiscal'] ?? null,
            'habilitacoes' => $dados['habilitacoes'] ?? null,
            'estado_civil' => $dados['estado_civil'] ?? null,
            'curso' => $dados['curso'] ?? null,
            'contacto_emergencia' => $dados['contacto_emergencia'] ?? null,
            'matricula_viatura' => $dados['matricula_viatura'] ?? null,
            'data_nascimento' => $dados['data_nascimento'] ?? null,
            'data_entrada' => $dados['data_entrada'] ?? null,
            'genero' => $dados['genero'] ?? null,
            'funcao' => $dados['funcao'] ?? null,
            'geografia' => $dados['geografia'] ?? null,
            'nivel_hierarquico' => $dados['nivel_hierarquico'] ?? null,
            'remuneracao' => $dados['remuneracao'] ?? null,
            'nome_abreviado' => $dados['nome_abreviado'] ?? null,
            'nif' => $dados['nif'] ?? null,
            'niss' => $dados['niss'] ?? null,
            'cc' => $dados['cc'] ?? null,
            'nacionalidade' => $dados['nacionalidade'] ?? null,
            'situacao_irs' => $dados['situacao_irs'] ?? null,
            'dependentes' => $dados['dependentes'] ?? null,
            'irs_jovem' => $dados['irs_jovem'] ?? null,
            'primeiro_ano_descontos' => $dados['primeiro_ano_descontos'] ?? null,
            'localidade' => $dados['localidade'] ?? null,
            'codigo_postal' => $dados['codigo_postal'] ?? null,
            'telemovel' => $dados['telemovel'] ?? null,
            'iban' => $dados['iban'] ?? null,
            'grau_relacionamento' => $dados['grau_relacionamento'] ?? null,
            'cartao_continente' => $dados['cartao_continente'] ?? null,
            'voucher_nos' => $dados['voucher_nos'] ?? null,
            'tipo_contrato' => $dados['tipo_contrato'] ?? null,
            'regime_horario' => $dados['regime_horario'] ?? null,
            'comprovativo_estado_civil' => $comprovativo ?? null
        ];
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
