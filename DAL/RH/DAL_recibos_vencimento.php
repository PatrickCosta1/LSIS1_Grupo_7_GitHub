<?php
require_once __DIR__ . '/../Database.php';

class DAL_RecibosRH {
    public function getAllColaboradores() {
        $pdo = Database::getConnection();
        // Apenas colaboradores e coordenadores ativos
        $sql = "SELECT c.id, c.nome, c.num_mecanografico, u.id as utilizador_id
                FROM colaboradores c
                INNER JOIN utilizadores u ON c.utilizador_id = u.id
                WHERE u.ativo = 1 AND (u.perfil_id = 2 OR u.perfil_id = 3)
                ORDER BY c.nome";
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getColaboradorById($colaborador_id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT c.*, u.id as utilizador_id FROM colaboradores c INNER JOIN utilizadores u ON c.utilizador_id = u.id WHERE c.id = ?");
        $stmt->execute([$colaborador_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function submeterRecibo($colaborador_id, $mes, $ano, $nome_ficheiro, $caminho_ficheiro) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO recibos_vencimento (colaborador_id, mes, ano, nome_ficheiro, data_submetido) VALUES (?, ?, ?, ?, NOW())");
        $ok = $stmt->execute([$colaborador_id, $mes, $ano, $caminho_ficheiro]);
        return $ok;
    }

    public function getRecibosPorColaborador($colaborador_id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT mes, ano, nome_ficheiro FROM recibos_vencimento WHERE colaborador_id = ? ORDER BY ano DESC, mes DESC");
        $stmt->execute([$colaborador_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>