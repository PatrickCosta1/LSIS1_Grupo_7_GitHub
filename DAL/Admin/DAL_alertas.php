<?php
require_once __DIR__ . '/../Database.php';

class AdminAlertasDAL {
    public function getAllAlertas() {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM alertas");
        return $stmt->fetchAll();
    }

    public function addAlerta($tipo, $descricao, $periodicidade_meses, $ativo) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO alertas (tipo, descricao, periodicidade_meses, ativo) VALUES (?, ?, ?, ?)");
        $stmt->execute([$tipo, $descricao, $periodicidade_meses, $ativo]);
        return $pdo->lastInsertId();
    }

    public function updateAlerta($id, $tipo, $descricao, $periodicidade_meses, $ativo) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE alertas SET tipo = ?, descricao = ?, periodicidade_meses = ?, ativo = ? WHERE id = ?");
        return $stmt->execute([$tipo, $descricao, $periodicidade_meses, $ativo, $id]);
    }

    public function removeAlerta($id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM alertas WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Associar alerta a perfis
    public function addAlertaPerfil($alerta_id, $perfil_id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT IGNORE INTO alertas_perfis (alerta_id, perfil_id) VALUES (?, ?)");
        return $stmt->execute([$alerta_id, $perfil_id]);
    }

    // Buscar alertas relevantes para um utilizador (por perfil)
    public function getAlertasParaUtilizador($perfil_id) {
        $pdo = Database::getConnection();
        $sql = "SELECT a.*
                FROM alertas a
                INNER JOIN alertas_perfis ap ON a.id = ap.alerta_id
                WHERE ap.perfil_id = ? AND a.ativo = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$perfil_id]);
        return $stmt->fetchAll();
    }

    // Marcar alerta como lido
    public function marcarComoLido($alerta_id, $utilizador_id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT IGNORE INTO alertas_lidos (alerta_id, utilizador_id, lido_em) VALUES (?, ?, NOW())");
        return $stmt->execute([$alerta_id, $utilizador_id]);
    }

    // Verificar se alerta está lido
    public function isAlertaLido($alerta_id, $utilizador_id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT 1 FROM alertas_lidos WHERE alerta_id = ? AND utilizador_id = ?");
        $stmt->execute([$alerta_id, $utilizador_id]);
        return (bool)$stmt->fetch();
    }

    public function getPerfisByAlerta($alerta_id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT p.id, p.nome FROM alertas_perfis ap INNER JOIN perfis p ON ap.perfil_id = p.id WHERE ap.alerta_id = ?");
        $stmt->execute([$alerta_id]);
        return $stmt->fetchAll();
    }
    public function updateAlertasPerfis($alerta_id, $perfis_ids) {
        $pdo = Database::getConnection();
        $pdo->prepare("DELETE FROM alertas_perfis WHERE alerta_id = ?")->execute([$alerta_id]);
        foreach ($perfis_ids as $perfil_id) {
            $pdo->prepare("INSERT IGNORE INTO alertas_perfis (alerta_id, perfil_id) VALUES (?, ?)")->execute([$alerta_id, $perfil_id]);
        }
    }
}
?>
