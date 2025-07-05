<?php
require_once __DIR__ . '/../Database.php';

class DAL_ExportarRH {
    public function getAllColaboradores() {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT *, 1 as ativo FROM colaboradores ORDER BY nome");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * PASSO 1: Buscar colaborador_id da tabela equipa_colaboradores onde equipa_id = $equipaId
     */
    public function getColaboradorIdsByEquipaId($equipaId) {
        $pdo = Database::getConnection();
        
        $stmt = $pdo->prepare("SELECT colaborador_id FROM equipa_colaboradores WHERE equipa_id = ?");
        $stmt->execute([$equipaId]);
        $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        error_log("DAL - Query: SELECT colaborador_id FROM equipa_colaboradores WHERE equipa_id = $equipaId");
        error_log("DAL - Colaborador IDs encontrados: " . implode(', ', $result));
        
        return $result;
    }
    
    /**
     * PASSO 2: Buscar todos os dados da tabela colaboradores onde colaborador.id IN ($colaboradorIds)
     */
    public function getColaboradoresByIds($colaboradorIds) {
        if (empty($colaboradorIds)) {
            return [];
        }
        
        $pdo = Database::getConnection();
        
        // Criar placeholders para a query IN (?,...,?)
        $placeholders = str_repeat('?,', count($colaboradorIds) - 1) . '?';
        
        $sql = "SELECT *, 1 as ativo FROM colaboradores WHERE id IN ($placeholders) ORDER BY nome";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($colaboradorIds);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        error_log("DAL - Query: $sql");
        error_log("DAL - Parâmetros: " . implode(', ', $colaboradorIds));
        error_log("DAL - Colaboradores retornados: " . count($result));
        
        return $result;
    }
    
    public function getColaboradoresPorPerfil($perfilId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT c.*, 1 as ativo 
            FROM colaboradores c
            INNER JOIN utilizadores u ON c.utilizador_id = u.id
            WHERE u.perfil_id = ?
            ORDER BY c.nome
        ");
        $stmt->execute([$perfilId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllEquipas() {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT id, nome FROM equipas ORDER BY nome");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllPerfis() {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT id, nome FROM perfis ORDER BY id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>