<?php
require_once __DIR__ . '/../Database.php';

class DAL_DashboardRH {
    public function getRHName($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT nome FROM colaboradores WHERE utilizador_id = ?");
        $stmt->execute([$userId]);
        $row = $stmt->fetch();
        return $row ? $row['nome'] : 'Equipa de RH';
    }

    // Para considerar o coordenador como membro da equipa, inclua o coordenador_id na seleção de membros
    public function getEquipasComMembros() {
        $pdo = Database::getConnection();
        $sql = "SELECT e.id, e.nome, 
            (
                SELECT COUNT(*) FROM equipa_colaboradores ec WHERE ec.equipa_id = e.id
            ) 
            + 
            (
                CASE WHEN e.coordenador_id IS NOT NULL 
                    AND e.coordenador_id NOT IN (
                        SELECT colaborador_id FROM equipa_colaboradores WHERE equipa_id = e.id
                    )
                THEN 1 ELSE 0 END
            ) as num_colaboradores
            FROM equipas e";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Para todas as queries que listam membros da equipa, inclua o coordenador se não estiver já em equipa_colaboradores
    public function getIdadesColaboradoresPorEquipa() {
        $pdo = Database::getConnection();
        $sql = "
            SELECT e.nome as equipa_nome, c.data_nascimento
            FROM equipa_colaboradores ec
            INNER JOIN colaboradores c ON ec.colaborador_id = c.id
            INNER JOIN equipas e ON ec.equipa_id = e.id
            WHERE c.data_nascimento IS NOT NULL AND c.data_nascimento != '' AND c.data_nascimento != '0000-00-00'
            UNION
            SELECT e.nome as equipa_nome, c.data_nascimento
            FROM equipas e
            INNER JOIN colaboradores c ON e.coordenador_id = c.id
            WHERE e.coordenador_id IS NOT NULL
              AND (e.coordenador_id NOT IN (SELECT colaborador_id FROM equipa_colaboradores WHERE equipa_id = e.id))
              AND c.data_nascimento IS NOT NULL AND c.data_nascimento != '' AND c.data_nascimento != '0000-00-00'
        ";
        $stmt = $pdo->query($sql);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data_nasc = $row['data_nascimento'];
            $idade = null;
            if ($data_nasc && $data_nasc !== '0000-00-00') {
                $idade = date_diff(date_create($data_nasc), date_create('now'))->y;
            }
            if ($idade !== null) {
                $result[] = ['equipa_nome' => $row['equipa_nome'], 'idade' => $idade];
            }
        }
        return $result;
    }

    public function getDistribuicaoNivelHierarquico() {
        $pdo = Database::getConnection();
        $sql = "SELECT nivel_hierarquico, COUNT(*) as total 
                FROM colaboradores 
                WHERE nivel_hierarquico IS NOT NULL AND nivel_hierarquico != '' 
                GROUP BY nivel_hierarquico";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retorna array associativo: [nivel_hierarquico => [ 'cargo' => N, ... ], ... ]
    public function getCargosPorNivelHierarquico() {
        $pdo = Database::getConnection();
        $sql = "SELECT nivel_hierarquico, cargo, COUNT(*) as total
                FROM colaboradores
                WHERE nivel_hierarquico IS NOT NULL AND nivel_hierarquico != '' AND cargo IS NOT NULL AND cargo != ''
                GROUP BY nivel_hierarquico, cargo";
        $stmt = $pdo->query($sql);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $nivel = $row['nivel_hierarquico'];
            $cargo = $row['cargo'];
            $total = (int)$row['total'];
            if (!isset($result[$nivel])) $result[$nivel] = [];
            $result[$nivel][$cargo] = $total;
        }
        return $result;
    }

    // Tempo médio na empresa por equipa (em anos)
    public function getTempoMedioEmpresaPorEquipa() {
        $pdo = Database::getConnection();
        $sql = "SELECT e.nome as equipa_nome, AVG(TIMESTAMPDIFF(YEAR, c.data_inicio_contrato, CURDATE())) as tempo_medio
                FROM equipa_colaboradores ec
                INNER JOIN colaboradores c ON ec.colaborador_id = c.id
                INNER JOIN equipas e ON ec.equipa_id = e.id
                WHERE c.data_inicio_contrato IS NOT NULL AND c.data_inicio_contrato != '0000-00-00'
                GROUP BY e.nome";
        $stmt = $pdo->query($sql);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[$row['equipa_nome']] = (float)$row['tempo_medio'];
        }
        return $result;
    }

    // Remuneração média por equipa
    public function getRemuneracaoMediaPorEquipa() {
        $pdo = Database::getConnection();
        $sql = "
            SELECT e.nome as equipa_nome, AVG(CAST(c.remuneracao AS DECIMAL(10,2))) as remuneracao_media
            FROM (
                SELECT ec.equipa_id, ec.colaborador_id FROM equipa_colaboradores ec
                UNION
                SELECT e.id as equipa_id, e.coordenador_id as colaborador_id
                FROM equipas e
                WHERE e.coordenador_id IS NOT NULL
                  AND (e.coordenador_id NOT IN (SELECT colaborador_id FROM equipa_colaboradores WHERE equipa_id = e.id))
            ) membros
            INNER JOIN equipas e ON membros.equipa_id = e.id
            INNER JOIN colaboradores c ON membros.colaborador_id = c.id
            WHERE c.remuneracao IS NOT NULL AND c.remuneracao != ''
            GROUP BY e.nome
        ";
        $stmt = $pdo->query($sql);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[$row['equipa_nome']] = (float)$row['remuneracao_media'];
        }
        return $result;
    }

    // Distribuição de género por equipa
    public function getDistribuicaoGeneroPorEquipa() {
        $pdo = Database::getConnection();
        $sql = "
            SELECT e.nome as equipa_nome, c.sexo, COUNT(*) as total
            FROM (
                SELECT ec.equipa_id, ec.colaborador_id FROM equipa_colaboradores ec
                UNION
                SELECT e.id as equipa_id, e.coordenador_id as colaborador_id
                FROM equipas e
                WHERE e.coordenador_id IS NOT NULL
                  AND (e.coordenador_id NOT IN (SELECT colaborador_id FROM equipa_colaboradores WHERE equipa_id = e.id))
            ) membros
            INNER JOIN equipas e ON membros.equipa_id = e.id
            INNER JOIN colaboradores c ON membros.colaborador_id = c.id
            WHERE c.sexo IS NOT NULL AND c.sexo != ''
            GROUP BY e.nome, c.sexo
        ";
        $stmt = $pdo->query($sql);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $eq = $row['equipa_nome'];
            $sexo = $row['sexo'];
            $total = (int)$row['total'];
            if (!isset($result[$eq])) $result[$eq] = [];
            $result[$eq][$sexo] = $total;
        }
        return $result;
    }
}
?>
