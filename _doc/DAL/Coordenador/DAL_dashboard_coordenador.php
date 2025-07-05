<?php
require_once __DIR__ . '/../Database.php';

class DAL_DashboardCoordenador {
    
    public function getEquipasByCoordenador($userId) {
        $pdo = Database::getConnection();
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
        $stmt = $pdo->prepare("
            SELECT 
                e.nome,
                COUNT(DISTINCT ec.colaborador_id) + 
                CASE WHEN e.responsavel_id NOT IN (SELECT colaborador_id FROM equipa_colaboradores WHERE equipa_id = e.id) THEN 1 ELSE 0 END as num_colaboradores
            FROM equipas e
            INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
            LEFT JOIN equipa_colaboradores ec ON e.id = ec.equipa_id
            WHERE coord.utilizador_id = ?
            GROUP BY e.id, e.nome
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getIdadesColaboradoresPorEquipa($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT 
                e.nome as equipa_nome, 
                TIMESTAMPDIFF(YEAR, col.data_nascimento, CURDATE()) as idade,
                col.nome as colaborador_nome
            FROM equipas e
            INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
            LEFT JOIN equipa_colaboradores ec ON e.id = ec.equipa_id
            LEFT JOIN colaboradores col ON ec.colaborador_id = col.id
            WHERE coord.utilizador_id = ? 
            AND col.data_nascimento IS NOT NULL 
            AND col.data_nascimento != '0000-00-00'
            
            UNION ALL
            
            SELECT 
                e.nome as equipa_nome,
                TIMESTAMPDIFF(YEAR, coord.data_nascimento, CURDATE()) as idade,
                coord.nome as colaborador_nome
            FROM equipas e
            INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
            WHERE coord.utilizador_id = ?
            AND e.responsavel_id NOT IN (SELECT colaborador_id FROM equipa_colaboradores WHERE equipa_id = e.id)
            AND coord.data_nascimento IS NOT NULL 
            AND coord.data_nascimento != '0000-00-00'
        ");
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTemposNaEmpresaPorEquipa($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT 
                e.nome as equipa_nome, 
                col.data_inicio_contrato
            FROM equipas e
            INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
            LEFT JOIN equipa_colaboradores ec ON e.id = ec.equipa_id
            LEFT JOIN colaboradores col ON ec.colaborador_id = col.id
            WHERE coord.utilizador_id = ? 
            AND col.data_inicio_contrato IS NOT NULL 
            AND col.data_inicio_contrato != '' 
            AND col.data_inicio_contrato != '0000-00-00'
            
            UNION ALL
            
            SELECT 
                e.nome as equipa_nome,
                coord.data_inicio_contrato
            FROM equipas e
            INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
            WHERE coord.utilizador_id = ?
            AND e.responsavel_id NOT IN (SELECT colaborador_id FROM equipa_colaboradores WHERE equipa_id = e.id)
            AND coord.data_inicio_contrato IS NOT NULL 
            AND coord.data_inicio_contrato != '' 
            AND coord.data_inicio_contrato != '0000-00-00'
        ");
        $stmt->execute([$userId, $userId]);
        
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data_inicio = $row['data_inicio_contrato'];
            $anos = null;
            if ($data_inicio && $data_inicio !== '0000-00-00') {
                $inicio = new DateTime($data_inicio);
                $hoje = new DateTime();
                $diff = $inicio->diff($hoje);
                $anos = $diff->y + ($diff->m / 12) + ($diff->d / 365);
            }
            if ($anos !== null) {
                $result[] = [
                    'equipa_nome' => $row['equipa_nome'],
                    'anos' => round($anos, 2)
                ];
            }
        }
        return $result;
    }

    public function getRemuneracaoMediaPorEquipa($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT 
                e.nome as equipa_nome,
                AVG(col.remuneracao) as remuneracao_media
            FROM equipas e
            INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
            LEFT JOIN equipa_colaboradores ec ON e.id = ec.equipa_id
            LEFT JOIN colaboradores col ON ec.colaborador_id = col.id
            WHERE coord.utilizador_id = ? 
            AND col.remuneracao IS NOT NULL 
            AND col.remuneracao > 0
            GROUP BY e.id, e.nome
            
            UNION ALL
            
            SELECT 
                e.nome as equipa_nome,
                coord.remuneracao as remuneracao_media
            FROM equipas e
            INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
            WHERE coord.utilizador_id = ?
            AND e.responsavel_id NOT IN (SELECT colaborador_id FROM equipa_colaboradores WHERE equipa_id = e.id)
            AND coord.remuneracao IS NOT NULL 
            AND coord.remuneracao > 0
        ");
        $stmt->execute([$userId, $userId]);
        
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $equipa = $row['equipa_nome'];
            $remuneracao = (float)$row['remuneracao_media'];
            
            if (!isset($result[$equipa])) {
                $result[$equipa] = [];
            }
            $result[$equipa][] = $remuneracao;
        }
        
        $medias = [];
        foreach ($result as $equipa => $valores) {
            $medias[$equipa] = array_sum($valores) / count($valores);
        }
        
        return $medias;
    }

    public function getDistribuicaoGeneroPorEquipa($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT 
                e.nome as equipa_nome,
                col.sexo,
                COUNT(*) as total
            FROM equipas e
            INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
            LEFT JOIN equipa_colaboradores ec ON e.id = ec.equipa_id
            LEFT JOIN colaboradores col ON ec.colaborador_id = col.id
            WHERE coord.utilizador_id = ? 
            AND col.sexo IS NOT NULL
            GROUP BY e.nome, col.sexo
            
            UNION ALL
            
            SELECT 
                e.nome as equipa_nome,
                coord.sexo,
                1 as total
            FROM equipas e
            INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
            WHERE coord.utilizador_id = ?
            AND e.responsavel_id NOT IN (SELECT colaborador_id FROM equipa_colaboradores WHERE equipa_id = e.id)
            AND coord.sexo IS NOT NULL
        ");
        $stmt->execute([$userId, $userId]);
        
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $equipa = $row['equipa_nome'];
            $sexo = $row['sexo'];
            $total = (int)$row['total'];
            
            if (!isset($result[$equipa])) {
                $result[$equipa] = [];
            }
            if (!isset($result[$equipa][$sexo])) {
                $result[$equipa][$sexo] = 0;
            }
            $result[$equipa][$sexo] += $total;
        }
        
        return $result;
    }

    public function getColaboradoresLocalidadePorEquipa($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT 
                e.nome as equipa_nome,
                col.localidade
            FROM equipas e
            INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
            LEFT JOIN equipa_colaboradores ec ON e.id = ec.equipa_id
            LEFT JOIN colaboradores col ON ec.colaborador_id = col.id
            WHERE coord.utilizador_id = ? 
            AND col.localidade IS NOT NULL
            
            UNION ALL
            
            SELECT 
                e.nome as equipa_nome,
                coord.localidade
            FROM equipas e
            INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
            WHERE coord.utilizador_id = ?
            AND e.responsavel_id NOT IN (SELECT colaborador_id FROM equipa_colaboradores WHERE equipa_id = e.id)
            AND coord.localidade IS NOT NULL
        ");
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDistribuicaoNivelHierarquico($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT 
                col.nivel_hierarquico,
                COUNT(*) as total
            FROM equipas e
            INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
            LEFT JOIN equipa_colaboradores ec ON e.id = ec.equipa_id
            LEFT JOIN colaboradores col ON ec.colaborador_id = col.id
            WHERE coord.utilizador_id = ? 
            AND col.nivel_hierarquico IS NOT NULL
            GROUP BY col.nivel_hierarquico
            
            UNION ALL
            
            SELECT 
                coord.nivel_hierarquico,
                1 as total
            FROM equipas e
            INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
            WHERE coord.utilizador_id = ?
            AND e.responsavel_id NOT IN (SELECT colaborador_id FROM equipa_colaboradores WHERE equipa_id = e.id)
            AND coord.nivel_hierarquico IS NOT NULL
        ");
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCargosPorNivelHierarquico($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT 
                col.nivel_hierarquico,
                col.cargo,
                COUNT(*) as total
            FROM equipas e
            INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
            LEFT JOIN equipa_colaboradores ec ON e.id = ec.equipa_id
            LEFT JOIN colaboradores col ON ec.colaborador_id = col.id
            WHERE coord.utilizador_id = ? 
            AND col.nivel_hierarquico IS NOT NULL 
            AND col.cargo IS NOT NULL
            GROUP BY col.nivel_hierarquico, col.cargo
            
            UNION ALL
            
            SELECT 
                coord.nivel_hierarquico,
                coord.cargo,
                1 as total
            FROM equipas e
            INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
            WHERE coord.utilizador_id = ?
            AND e.responsavel_id NOT IN (SELECT colaborador_id FROM equipa_colaboradores WHERE equipa_id = e.id)
            AND coord.nivel_hierarquico IS NOT NULL 
            AND coord.cargo IS NOT NULL
        ");
        $stmt->execute([$userId, $userId]);
        
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $nivel = $row['nivel_hierarquico'];
            $cargo = $row['cargo'];
            $total = (int)$row['total'];
            
            if (!isset($result[$nivel])) {
                $result[$nivel] = [];
            }
            if (!isset($result[$nivel][$cargo])) {
                $result[$nivel][$cargo] = 0;
            }
            $result[$nivel][$cargo] += $total;
        }
        
        return $result;
    }

    public function getCoordenadorName($userId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT nome FROM colaboradores WHERE utilizador_id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['nome'] : 'Coordenador';
    }

    public function getTaxaRetencaoPorEquipa($userId, $ano = null) {
        if ($ano === null) $ano = date('Y');
        
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT 
                e.nome as equipa_nome,
                COUNT(DISTINCT col.id) as total_colaboradores,
                SUM(CASE 
                    WHEN col.data_inicio_contrato IS NOT NULL 
                    AND col.data_inicio_contrato != '0000-00-00' 
                    AND YEAR(col.data_inicio_contrato) <= ?
                    AND (col.data_fim_contrato IS NULL 
                         OR col.data_fim_contrato = '0000-00-00' 
                         OR YEAR(col.data_fim_contrato) > ?)
                    THEN 1 ELSE 0 
                END) as retidos
            FROM equipas e
            INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
            LEFT JOIN equipa_colaboradores ec ON e.id = ec.equipa_id
            LEFT JOIN colaboradores col ON ec.colaborador_id = col.id
            WHERE coord.utilizador_id = ?
            AND col.id IS NOT NULL
            GROUP BY e.id, e.nome
            
            UNION ALL
            
            SELECT 
                e.nome as equipa_nome,
                1 as total_colaboradores,
                CASE 
                    WHEN coord.data_inicio_contrato IS NOT NULL 
                    AND coord.data_inicio_contrato != '0000-00-00' 
                    AND YEAR(coord.data_inicio_contrato) <= ?
                    AND (coord.data_fim_contrato IS NULL 
                         OR coord.data_fim_contrato = '0000-00-00' 
                         OR YEAR(coord.data_fim_contrato) > ?)
                    THEN 1 ELSE 0 
                END as retidos
            FROM equipas e
            INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
            WHERE coord.utilizador_id = ?
            AND e.responsavel_id NOT IN (
                SELECT COALESCE(colaborador_id, 0) FROM equipa_colaboradores WHERE equipa_id = e.id
            )
        ");
        
        $stmt->execute([$ano, $ano, $userId, $ano, $ano, $userId]);
        
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $equipa = $row['equipa_nome'];
            $total = (int)$row['total_colaboradores'];
            $retidos = (int)$row['retidos'];
            
            if (!isset($result[$equipa])) {
                $result[$equipa] = ['total' => 0, 'retidos' => 0];
            }
            $result[$equipa]['total'] += $total;
            $result[$equipa]['retidos'] += $retidos;
        }
        
        $taxas = [];
        foreach ($result as $equipa => $dados) {
            $taxas[$equipa] = $dados['total'] > 0 ? round($dados['retidos'] / $dados['total'] * 100, 1) : 0;
        }
        
        return $taxas;
    }

    public function getTaxaRetencaoGlobal($userId, $ano = null) {
        if ($ano === null) $ano = date('Y');
        
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT 
                COUNT(DISTINCT col.id) as total_colaboradores,
                SUM(CASE 
                    WHEN col.data_inicio_contrato IS NOT NULL 
                    AND col.data_inicio_contrato != '0000-00-00' 
                    AND YEAR(col.data_inicio_contrato) <= ?
                    AND (col.data_fim_contrato IS NULL 
                         OR col.data_fim_contrato = '0000-00-00' 
                         OR YEAR(col.data_fim_contrato) > ?)
                    THEN 1 ELSE 0 
                END) as retidos
            FROM equipas e
            INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
            LEFT JOIN equipa_colaboradores ec ON e.id = ec.equipa_id
            LEFT JOIN colaboradores col ON ec.colaborador_id = col.id
            WHERE coord.utilizador_id = ?
            AND col.id IS NOT NULL
            
            UNION ALL
            
            SELECT 
                1 as total_colaboradores,
                CASE 
                    WHEN coord.data_inicio_contrato IS NOT NULL 
                    AND coord.data_inicio_contrato != '0000-00-00' 
                    AND YEAR(coord.data_inicio_contrato) <= ?
                    AND (coord.data_fim_contrato IS NULL 
                         OR coord.data_fim_contrato = '0000-00-00' 
                         OR YEAR(coord.data_fim_contrato) > ?)
                    THEN 1 ELSE 0 
                END as retidos
            FROM equipas e
            INNER JOIN colaboradores coord ON e.responsavel_id = coord.id
            WHERE coord.utilizador_id = ?
            AND e.responsavel_id NOT IN (
                SELECT COALESCE(colaborador_id, 0) FROM equipa_colaboradores WHERE equipa_id = e.id
            )
        ");
        
        $stmt->execute([$ano, $ano, $userId, $ano, $ano, $userId]);
        
        $totalColaboradores = 0;
        $totalRetidos = 0;
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $totalColaboradores += (int)$row['total_colaboradores'];
            $totalRetidos += (int)$row['retidos'];
        }
        
        return $totalColaboradores > 0 ? round($totalRetidos / $totalColaboradores * 100, 1) : 0;
    }
}
?>