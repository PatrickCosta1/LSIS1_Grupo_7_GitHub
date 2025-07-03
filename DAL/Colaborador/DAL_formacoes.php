<?php
require_once __DIR__ . '/../Database.php';

class DALFormacoes
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function listarFormacoesFuturas()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM formacoes WHERE data_inicio >= CURDATE() ORDER BY data_inicio ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFormacaoById($formacaoId) {
        $stmt = $this->pdo->prepare("SELECT * FROM formacoes WHERE id = ?");
        $stmt->execute([$formacaoId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getColaboradorById($colaboradorId) {
        $stmt = $this->pdo->prepare("SELECT utilizador_id FROM colaboradores WHERE id = ?");
        $stmt->execute([$colaboradorId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function inscreverColaborador($colaboradorId, $formacaoId)
    {
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO inscricao_formacoes (colaborador_id, formacao_id, data_inscricao) VALUES (?, ?, NOW())"
            );
            return $stmt->execute([$colaboradorId, $formacaoId]);
        } catch (PDOException $e) {
            // Se já estiver inscrito, retorna false
            return false;
        }
    }

    public function jaInscrito($colaboradorId, $formacaoId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM inscricao_formacoes WHERE colaborador_id = ? AND formacao_id = ?"
        );
        $stmt->execute([$colaboradorId, $formacaoId]);
        return $stmt->fetchColumn() > 0;
    }

    public function getFormacoesPorColaborador($colaboradorId) {
        try {
            // Verificar se a tabela inscricao_formacoes existe
            $checkTable = $this->pdo->query("SHOW TABLES LIKE 'inscricao_formacoes'");
            if ($checkTable->rowCount() == 0) {
                return [];
            }
            
            // Query corrigida: fazer JOIN entre inscricao_formacoes e formacoes
            $stmt = $this->pdo->prepare("
                SELECT 
                    f.id,
                    f.nome,
                    f.descricao,
                    f.data_inicio,
                    f.data_fim,
                    f.horario_semanal,
                    i.data_inscricao,
                    i.formacao_nome
                FROM inscricao_formacoes i
                INNER JOIN formacoes f ON i.formacao_id = f.id
                WHERE i.colaborador_id = ?
                ORDER BY f.data_inicio ASC
            ");
            $stmt->execute([$colaboradorId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar formações do colaborador: " . $e->getMessage());
            return [];
        }
    }
}
?>
