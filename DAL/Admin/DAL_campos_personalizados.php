<?php
require_once __DIR__ . '/../Database.php';

class AdminCamposPersonalizadosManagerDAL {
    public function getAllCampos() {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM campos_personalizados");
        return $stmt->fetchAll();
    }

    public function addCampo($nome, $tipo) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO campos_personalizados (nome, tipo) VALUES (?, ?)");
        return $stmt->execute([$nome, $tipo]);
    }

    public function updateCampo($id, $nome, $tipo) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE campos_personalizados SET nome = ?, tipo = ? WHERE id = ?");
        return $stmt->execute([$nome, $tipo, $id]);
    }

    public function removeCampo($id) {
        $pdo = Database::getConnection();
        // Remove valores associados
        $pdo->prepare("DELETE FROM campos_personalizados_valores WHERE campo_id = ?")->execute([$id]);
        // Remove o campo
        $stmt = $pdo->prepare("DELETE FROM campos_personalizados WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getValoresByColaborador($colaborador_id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT v.*, c.nome, c.tipo FROM campos_personalizados_valores v
            INNER JOIN campos_personalizados c ON v.campo_id = c.id
            WHERE v.colaborador_id = ?");
        $stmt->execute([$colaborador_id]);
        return $stmt->fetchAll();
    }

    public function setValorCampo($colaborador_id, $campo_id, $valor) {
        $pdo = Database::getConnection();
        // Se já existe, faz update, senão insere
        $stmt = $pdo->prepare("SELECT 1 FROM campos_personalizados_valores WHERE colaborador_id = ? AND campo_id = ?");
        $stmt->execute([$colaborador_id, $campo_id]);
        if ($stmt->fetch()) {
            $stmt2 = $pdo->prepare("UPDATE campos_personalizados_valores SET valor = ? WHERE colaborador_id = ? AND campo_id = ?");
            return $stmt2->execute([$valor, $colaborador_id, $campo_id]);
        } else {
            $stmt2 = $pdo->prepare("INSERT INTO campos_personalizados_valores (colaborador_id, campo_id, valor) VALUES (?, ?, ?)");
            return $stmt2->execute([$colaborador_id, $campo_id, $valor]);
        }
    }
}
?>
