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

    // Corrigido: Remover coordenador_id
    public function getEquipasComMembros() {
        $pdo = Database::getConnection();
        $sql = "SELECT e.id, e.nome, 
            (
                SELECT COUNT(*) FROM equipa_colaboradores ec WHERE ec.equipa_id = e.id
            ) as num_colaboradores
            FROM equipas e";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Corrigido: Remover coordenador_id
    public function getIdadesColaboradoresPorEquipa() {
        $pdo = Database::getConnection();
        $sql = "
            SELECT e.nome as equipa_nome, c.data_nascimento
            FROM equipa_colaboradores ec
            INNER JOIN colaboradores c ON ec.colaborador_id = c.id
            INNER JOIN equipas e ON ec.equipa_id = e.id
            WHERE c.data_nascimento IS NOT NULL AND c.data_nascimento != '' AND c.data_nascimento != '0000-00-00'
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

    // Corrigido: Remover coordenador_id
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

    // Corrigido: Remover coordenador_id
    public function getRemuneracaoMediaPorEquipa() {
        $pdo = Database::getConnection();
        $sql = "
            SELECT e.nome as equipa_nome, AVG(CAST(c.remuneracao AS DECIMAL(10,2))) as remuneracao_media
            FROM equipa_colaboradores ec
            INNER JOIN equipas e ON ec.equipa_id = e.id
            INNER JOIN colaboradores c ON ec.colaborador_id = c.id
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

    // Corrigido: Remover coordenador_id
    public function getDistribuicaoGeneroPorEquipa() {
        $pdo = Database::getConnection();
        $sql = "
            SELECT e.nome as equipa_nome, c.sexo, COUNT(*) as total
            FROM equipa_colaboradores ec
            INNER JOIN equipas e ON ec.equipa_id = e.id
            INNER JOIN colaboradores c ON ec.colaborador_id = c.id
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
    public function getNomesColaboradoresPorEquipa() {
        $pdo = Database::getConnection();
        $sql = "
            SELECT e.nome as equipa_nome, c.nome as colaborador_nome
            FROM equipa_colaboradores ec
            INNER JOIN equipas e ON ec.equipa_id = e.id
            INNER JOIN colaboradores c ON ec.colaborador_id = c.id
            ORDER BY e.nome, c.nome
        ";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Novo: distribuição geográfica por localidade
    public function getDistribuicaoGeografica() {
        $pdo = Database::getConnection();
        $sql = "
            SELECT c.localidade, COUNT(*) as total
            FROM colaboradores c
            WHERE c.localidade IS NOT NULL AND c.localidade != ''
            GROUP BY c.localidade
            ORDER BY total DESC
        ";
        $stmt = $pdo->query($sql);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[$row['localidade']] = (int)$row['total'];
        }
        return $result;
    }

    // Novo: localidades dos colaboradores por equipa
    public function getLocalidadesPorEquipa() {
        $pdo = Database::getConnection();
        $sql = "
            SELECT e.nome as equipa_nome, c.localidade
            FROM equipa_colaboradores ec
            INNER JOIN equipas e ON ec.equipa_id = e.id
            INNER JOIN colaboradores c ON ec.colaborador_id = c.id
            WHERE c.localidade IS NOT NULL AND c.localidade != ''
        ";
        $stmt = $pdo->query($sql);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $eq = $row['equipa_nome'];
            $loc = $row['localidade'];
            if (!isset($result[$eq])) $result[$eq] = [];
            $result[$eq][] = $loc;
        }
        return $result;
    }
}
// Nenhuma alteração necessária para centralização dos gráficos
?>