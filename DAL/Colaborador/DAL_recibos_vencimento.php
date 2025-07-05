<?php
require_once __DIR__ . '/../../DAL/Database.php';

class RecibosVencimentoDAL {
    public function listarRecibosPorColaborador($colaboradorId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id, nome_ficheiro, data_submetido 
                              FROM recibos_vencimento 
                              WHERE colaborador_id = ? 
                              ORDER BY data_submetido DESC");
        $stmt->execute([$colaboradorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}