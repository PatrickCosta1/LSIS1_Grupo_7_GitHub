<?php
require_once __DIR__ . '/../Database.php';

class DAL_DashboardCoordenador {
    public function getCoordenadorName($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT nome FROM colaboradores WHERE utilizador_id = ?");
        $stmt->execute([$userId]);
        $row = $stmt->fetch();
        return $row ? $row['nome'] : '';
    }

    public function getEquipasByCoordenador($userId) {
        $pdo = Database::getConnection();
        // Corrigido: buscar primeiro o colaborador_id pelo utilizador_id, depois as equipas
        $stmt = $pdo->prepare("
            SELECT e.id, e.nome 
            FROM equipas e 
            INNER JOIN colaboradores c ON e.responsavel_id = c.id 
            WHERE c.utilizador_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEquipasComMembros($userId) {
        $pdo = Database::getConnection();
        // Corrigido: buscar pela ligação utilizador_id -> colaborador_id -> responsavel_id
        $stmt = $pdo->prepare("
            SELECT e.id, e.nome, 
                (
                    SELECT COUNT(*) FROM equipa_colaboradores ec WHERE ec.equipa_id = e.id
                ) 
                + 
                (
                    CASE WHEN e.responsavel_id IS NOT NULL 
                        AND e.responsavel_id NOT IN (
                            SELECT colaborador_id FROM equipa_colaboradores WHERE equipa_id = e.id
                        )
                    THEN 1 ELSE 0 END
                ) as num_colaboradores
            FROM equipas e 
            INNER JOIN colaboradores c ON e.responsavel_id = c.id 
            WHERE c.utilizador_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getIdadesColaboradoresPorEquipa($userId) {
        $pdo = Database::getConnection();
        // Corrigido: usar a ligação correta utilizador_id -> colaborador_id
        $stmt = $pdo->prepare("
            SELECT e.nome as equipa_nome, col.data_nascimento
            FROM equipa_colaboradores ec
            INNER JOIN colaboradores col ON ec.colaborador_id = col.id
            INNER JOIN equipas e ON ec.equipa_id = e.id
            INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
            WHERE coord.utilizador_id = ? AND col.data_nascimento IS NOT NULL AND col.data_nascimento != '' AND col.data_nascimento != '0000-00-00'
            UNION
            SELECT e.nome as equipa_nome, coord.data_nascimento
            FROM equipas e
            INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
            WHERE coord.utilizador_id = ?
              AND e.responsavel_id IS NOT NULL
              AND (e.responsavel_id NOT IN (SELECT colaborador_id FROM equipa_colaboradores WHERE equipa_id = e.id))
              AND coord.data_nascimento IS NOT NULL AND coord.data_nascimento != '' AND coord.data_nascimento != '0000-00-00'
        ");
        $stmt->execute([$userId, $userId]);
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

    public function getDistribuicaoNivelHierarquico($userId) {
        $pdo = Database::getConnection();
        // Corrigido: usar a ligação correta utilizador_id -> colaborador_id
        $stmt = $pdo->prepare("
            SELECT nivel_hierarquico, COUNT(*) as total FROM (
                SELECT col.nivel_hierarquico
                FROM equipa_colaboradores ec
                INNER JOIN colaboradores col ON ec.colaborador_id = col.id
                INNER JOIN equipas e ON ec.equipa_id = e.id
                INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
                WHERE coord.utilizador_id = ? AND col.nivel_hierarquico IS NOT NULL AND col.nivel_hierarquico != ''
                UNION ALL
                SELECT coord.nivel_hierarquico
                FROM equipas e
                INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
                WHERE coord.utilizador_id = ?
                  AND e.responsavel_id IS NOT NULL
                  AND (e.responsavel_id NOT IN (SELECT colaborador_id FROM equipa_colaboradores WHERE equipa_id = e.id))
                  AND coord.nivel_hierarquico IS NOT NULL AND coord.nivel_hierarquico != ''
            ) as sub
            GROUP BY nivel_hierarquico
        ");
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCargosPorNivelHierarquico($userId) {
        $pdo = Database::getConnection();
        // Corrigido: usar a ligação correta utilizador_id -> colaborador_id
        $stmt = $pdo->prepare("
            SELECT nivel_hierarquico, cargo, COUNT(*) as total FROM (
                SELECT col.nivel_hierarquico, col.cargo
                FROM equipa_colaboradores ec
                INNER JOIN colaboradores col ON ec.colaborador_id = col.id
                INNER JOIN equipas e ON ec.equipa_id = e.id
                INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
                WHERE coord.utilizador_id = ? AND col.nivel_hierarquico IS NOT NULL AND col.nivel_hierarquico != '' AND col.cargo IS NOT NULL AND col.cargo != ''
                UNION ALL
                SELECT coord.nivel_hierarquico, coord.cargo
                FROM equipas e
                INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
                WHERE coord.utilizador_id = ?
                  AND e.responsavel_id IS NOT NULL
                  AND (e.responsavel_id NOT IN (SELECT colaborador_id FROM equipa_colaboradores WHERE equipa_id = e.id))
                  AND coord.nivel_hierarquico IS NOT NULL AND coord.nivel_hierarquico != '' AND coord.cargo IS NOT NULL AND coord.cargo != ''
            ) as sub
            GROUP BY nivel_hierarquico, cargo
        ");
        $stmt->execute([$userId, $userId]);
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

    public function getTemposNaEmpresaPorEquipa($userId) {
        $pdo = Database::getConnection();
        // Corrigido: usar a ligação correta utilizador_id -> colaborador_id
        $stmt = $pdo->prepare("
            SELECT equipa_nome, data_inicio_contrato FROM (
                SELECT e.nome as equipa_nome, col.data_inicio_contrato
                FROM equipa_colaboradores ec
                INNER JOIN colaboradores col ON ec.colaborador_id = col.id
                INNER JOIN equipas e ON ec.equipa_id = e.id
                INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
                WHERE coord.utilizador_id = ? AND col.data_inicio_contrato IS NOT NULL AND col.data_inicio_contrato != '' AND col.data_inicio_contrato != '0000-00-00'
                UNION ALL
                SELECT e.nome as equipa_nome, coord.data_inicio_contrato
                FROM equipas e
                INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
                WHERE coord.utilizador_id = ?
                  AND e.responsavel_id IS NOT NULL
                  AND (e.responsavel_id NOT IN (SELECT colaborador_id FROM equipa_colaboradores WHERE equipa_id = e.id))
                  AND coord.data_inicio_contrato IS NOT NULL AND coord.data_inicio_contrato != '' AND coord.data_inicio_contrato != '0000-00-00'
            ) as sub
        ");
        $stmt->execute([$userId, $userId]);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data_inicio = $row['data_inicio_contrato'];
            $anos = null;
            if ($data_inicio && $data_inicio !== '0000-00-00') {
                $diff = date_diff(date_create($data_inicio), date_create('now'));
                $anos = $diff->y + ($diff->m / 12);
            }
            if ($anos !== null) {
                $result[] = ['equipa_nome' => $row['equipa_nome'], 'anos' => round($anos, 2)];
            }
        }
        return $result;
    }

    public function getColaboradoresByEquipa($equipaId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT c.id, c.nome, c.email, c.cargo
            FROM equipa_colaboradores ec
            INNER JOIN colaboradores c ON ec.colaborador_id = c.id
            WHERE ec.equipa_id = ?
            UNION
            SELECT c.id, c.nome, c.email, c.cargo
            FROM equipas e
            INNER JOIN colaboradores c ON e.responsavel_id = c.id
            WHERE e.id = ?
              AND e.responsavel_id IS NOT NULL
              AND NOT EXISTS (
                  SELECT 1 FROM equipa_colaboradores ec2 WHERE ec2.equipa_id = e.id AND ec2.colaborador_id = e.responsavel_id
              )
        ");
        $stmt->execute([$equipaId, $equipaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>