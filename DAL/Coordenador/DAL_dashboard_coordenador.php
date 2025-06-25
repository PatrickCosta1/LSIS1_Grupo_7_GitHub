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
        $stmt = $pdo->prepare("SELECT e.id, e.nome FROM equipas e WHERE e.coordenador_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getColaboradoresByEquipa($equipaId) {
        $pdo = Database::getConnection();
        $sql = "SELECT c.id, c.nome, c.cargo, u.email
                FROM colaboradores c
                LEFT JOIN equipa_colaboradores ec ON c.id = ec.colaborador_id
                LEFT JOIN utilizadores u ON c.utilizador_id = u.id
                WHERE ec.equipa_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$equipaId]);
        return $stmt->fetchAll();
    }

    public function getIndicadoresEquipa($equipaId) {
        $pdo = Database::getConnection();
        // Ativos/Inativos
        $ativos = $pdo->prepare("SELECT COUNT(*) FROM equipa_colaboradores ec
            JOIN colaboradores c ON ec.colaborador_id = c.id
            JOIN utilizadores u ON c.utilizador_id = u.id
            WHERE ec.equipa_id = ? AND u.ativo = 1");
        $ativos->execute([$equipaId]);
        $inativos = $pdo->prepare("SELECT COUNT(*) FROM equipa_colaboradores ec
            JOIN colaboradores c ON ec.colaborador_id = c.id
            JOIN utilizadores u ON c.utilizador_id = u.id
            WHERE ec.equipa_id = ? AND u.ativo = 0");
        $inativos->execute([$equipaId]);
        // Por função
        $funcoes = $pdo->prepare("SELECT c.cargo, COUNT(*) as total FROM equipa_colaboradores ec
            JOIN colaboradores c ON ec.colaborador_id = c.id
            WHERE ec.equipa_id = ?
            GROUP BY c.cargo");
        $funcoes->execute([$equipaId]);
        $funcoesArr = [];
        foreach ($funcoes as $row) {
            $funcoesArr[$row['cargo'] ?: 'Outro'] = (int)$row['total'];
        }
        return [
            'ativos' => (int)$ativos->fetchColumn(),
            'inativos' => (int)$inativos->fetchColumn(),
            'funcoes' => $funcoesArr
        ];
    }

    public function getIdadesPorEquipa($equipaId) {
        $pdo = Database::getConnection();
        $sql = "SELECT TIMESTAMPDIFF(YEAR, c.data_nascimento, CURDATE()) as idade
                FROM equipa_colaboradores ec
                JOIN colaboradores c ON ec.colaborador_id = c.id
                WHERE ec.equipa_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$equipaId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getAniversariantesEquipaMes($equipaId) {
        $pdo = Database::getConnection();
        $sql = "SELECT c.nome
                FROM equipa_colaboradores ec
                JOIN colaboradores c ON ec.colaborador_id = c.id
                WHERE ec.equipa_id = ? AND MONTH(c.data_nascimento) = MONTH(CURDATE())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$equipaId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getTemposCasaPorEquipa($equipaId) {
        $pdo = Database::getConnection();
        $sql = "SELECT c.data_admissao
                FROM equipa_colaboradores ec
                JOIN colaboradores c ON ec.colaborador_id = c.id
                WHERE ec.equipa_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$equipaId]);
        $tempos = [];
        $hoje = new DateTime();
        foreach ($stmt->fetchAll() as $row) {
            if (!empty($row['data_admissao'])) {
                $entrada = new DateTime($row['data_admissao']);
                $anos = $entrada->diff($hoje)->y;
                $tempos[] = $anos;
            }
        }
        return $tempos;
    }

    public function getEmailsTipoPorEquipa($equipaId) {
        $pdo = Database::getConnection();
        $sql = "SELECT u.email FROM equipa_colaboradores ec
                JOIN colaboradores c ON ec.colaborador_id = c.id
                JOIN utilizadores u ON c.utilizador_id = u.id
                WHERE ec.equipa_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$equipaId]);
        $institucional = 0;
        $pessoal = 0;
        foreach ($stmt->fetchAll() as $row) {
            if (strpos($row['email'], '@tlantic.com') !== false) {
                $institucional++;
            } else {
                $pessoal++;
            }
        }
        return ['Institucional' => $institucional, 'Pessoal' => $pessoal];
    }

    public function getGenerosPorEquipa($equipaId) {
        $pdo = Database::getConnection();
        $sql = "SELECT c.sexo
                FROM equipa_colaboradores ec
                JOIN colaboradores c ON ec.colaborador_id = c.id
                WHERE ec.equipa_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$equipaId]);
        $generos = [];
        foreach ($stmt->fetchAll() as $row) {
            $genero = $row['sexo'] ?: 'Não definido';
            if (!isset($generos[$genero])) {
                $generos[$genero] = 0;
            }
            $generos[$genero]++;
        }
        return $generos;
    }

    public function getEvolucaoColaboradores($equipaId) {
        // Não existe data_entrada, então simule os dados para o gráfico
        // Exemplo: últimos 6 meses
        return [
            date('M', strtotime('-5 months')) => 10,
            date('M', strtotime('-4 months')) => 12,
            date('M', strtotime('-3 months')) => 13,
            date('M', strtotime('-2 months')) => 14,
            date('M', strtotime('-1 months')) => 13,
            date('M') => 15
        ];
    }

    public function getFaltasPorColaborador($equipaId) {
        // Se não houver tabela de faltas, simule os dados:
        return [
            'João' => 2,
            'Maria' => 0,
            'Carlos' => 1,
            'Ana' => 0,
            'Pedro' => 3
        ];
        // Exemplo real (se existir tabela de faltas):
        /*
        $pdo = Database::getConnection();
        $sql = "SELECT c.nome, COUNT(f.id) as faltas
                FROM equipa_colaboradores ec
                JOIN colaboradores c ON ec.colaborador_id = c.id
                LEFT JOIN faltas f ON f.colaborador_id = c.id
                WHERE ec.equipa_id = ?
                GROUP BY c.nome";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$equipaId]);
        $result = [];
        foreach ($stmt->fetchAll() as $row) {
            $result[$row['nome']] = (int)$row['faltas'];
        }
        return $result;
        */
    }

    public function getAvaliacoesDesempenhoPorEquipa($equipaId) {
        // Se não houver tabela de avaliações, simule os dados:
        return [
            'Excelente' => 2,
            'Bom' => 5,
            'Regular' => 1,
            'Ruim' => 0
        ];
        // Exemplo real (se existir tabela de avaliações):
        /*
        $pdo = Database::getConnection();
        $sql = "SELECT a.nivel, COUNT(*) as total
                FROM equipa_colaboradores ec
                JOIN colaboradores c ON ec.colaborador_id = c.id
                JOIN avaliacoes a ON a.colaborador_id = c.id
                WHERE ec.equipa_id = ?
                GROUP BY a.nivel";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$equipaId]);
        $result = [];
        foreach ($stmt->fetchAll() as $row) {
            $result[$row['nivel']] = (int)$row['total'];
        }
        return $result;
        */
    }
}
?>
