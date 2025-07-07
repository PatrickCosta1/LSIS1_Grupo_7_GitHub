<?php
require_once __DIR__ . '/../Database.php';

class DAL_Perfil {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Database::getConnection();
    }
    
    public function getUserById($id) {
        $stmt = $this->pdo->prepare("SELECT u.*, c.nome FROM utilizadores u LEFT JOIN colaboradores c ON u.id = c.utilizador_id WHERE u.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateUserProfile($id, $nome, $email, $username) {
        // Atualiza utilizadores
        $stmt1 = $this->pdo->prepare("UPDATE utilizadores SET email = ?, username = ? WHERE id = ?");
        $ok1 = $stmt1->execute([$email, $username, $id]);

        // Atualiza colaboradores
        $stmt2 = $this->pdo->prepare("UPDATE colaboradores SET nome = ? WHERE utilizador_id = ?");
        $stmt2->execute([$nome, $id]);
        // Se não atualizou nenhuma linha, faz insert
        if ($stmt2->rowCount() === 0) {
            $stmt3 = $this->pdo->prepare("INSERT INTO colaboradores (utilizador_id, nome) VALUES (?, ?)");
            $stmt3->execute([$id, $nome]);
        }
        return $ok1;
    }

    public function getPasswordHashById($id) {
        $stmt = $this->pdo->prepare("SELECT password FROM utilizadores WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? $row['password'] : null;
    }

    public function updatePassword($id, $newHash) {
        $stmt = $this->pdo->prepare("UPDATE utilizadores SET password = ? WHERE id = ?");
        return $stmt->execute([$newHash, $id]);
    }

    // Métodos para buscar formações e férias
    public function getFormacoesPorColaborador($colaboradorId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    f.id,
                    f.nome,
                    f.descricao,
                    f.data_inicio,
                    f.data_fim,
                    f.horario_semanal,
                    i.data_inscricao
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

    public function getPedidosFeriasPorColaborador($colaboradorId) {
        try {
            // Debug melhorado: verificar o parâmetro recebido
            error_log("=== getPedidosFeriasPorColaborador ===");
            error_log("Parâmetro colaboradorId recebido: " . $colaboradorId);
            
<<<<<<< Updated upstream
            // Verificar se existem registros na tabela para este colaborador específico
            $debugStmt = $this->pdo->prepare("SELECT * FROM pedidos_ferias WHERE colaborador_id = ?");
            $debugStmt->execute([$colaboradorId]);
            $allResults = $debugStmt->fetchAll(PDO::FETCH_ASSOC);
=======
            // Query com LEFT JOIN para garantir compatibilidade e buscar todos os campos possíveis
            $stmt = $this->pdo->prepare("
                SELECT pf.id, pf.colaborador_id, pf.data_inicio, pf.data_fim, pf.data_pedido, COALESCE(pf.estado, 'pendente') as estado FROM pedidos_ferias pf WHERE pf.colaborador_id = ? ORDER BY pf.data_pedido DESC
            ");
            $stmt->execute([$colaboradorId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
>>>>>>> Stashed changes
            
            error_log("Registros encontrados para colaborador_id " . $colaboradorId . ": " . count($allResults));
            
            if (!empty($allResults)) {
                error_log("Primeiro registro encontrado: " . print_r($allResults[0], true));
                
                // Mostrar TODOS os registros para debug
                foreach ($allResults as $index => $record) {
                    error_log("Registro " . ($index + 1) . ": ID=" . $record['id'] . ", colaborador_id=" . $record['colaborador_id'] . ", data_inicio=" . $record['data_inicio'] . ", data_fim=" . $record['data_fim'] . ", status=" . ($record['status'] ?? 'NULL'));
                }
            } else {
                error_log("NENHUM registro encontrado na tabela pedidos_ferias para colaborador_id = " . $colaboradorId);
                
                // Debug adicional: verificar se existem registros na tabela
                $totalStmt = $this->pdo->query("SELECT COUNT(*) as total FROM pedidos_ferias");
                $totalRecords = $totalStmt->fetch()['total'];
                error_log("Total de registros na tabela pedidos_ferias: " . $totalRecords);
                
                // Mostrar alguns registros da tabela para verificar a estrutura
                $sampleStmt = $this->pdo->query("SELECT * FROM pedidos_ferias LIMIT 5");
                $sampleResults = $sampleStmt->fetchAll(PDO::FETCH_ASSOC);
                error_log("Amostra de registros da tabela pedidos_ferias: " . print_r($sampleResults, true));
            }
            
            return $allResults;
            
        } catch (PDOException $e) {
            error_log("ERRO na query getPedidosFeriasPorColaborador: " . $e->getMessage());
            error_log("SQL Error Code: " . $e->getCode());
            return [];
        }
    }

    public function getColaboradorByUserId($userId) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM colaboradores WHERE utilizador_id = ?");
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            error_log("Colaborador encontrado para user_id " . $userId . ": " . print_r($result, true));
            
            return $result;
        } catch (PDOException $e) {
            error_log("Erro ao buscar colaborador: " . $e->getMessage());
            return false;
        }
    }
}
?>