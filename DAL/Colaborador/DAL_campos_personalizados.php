<?php
require_once __DIR__ . '/../Database.php';

class DAL_CamposPersonalizadosColaborador {
    public function getValoresByColaboradorId($colaborador_id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM valores_campos_personalizados WHERE colaborador_id = ?");
        $stmt->execute([$colaborador_id]);
        return $stmt->fetchAll();
    }
    public function guardarValor($colaborador_id, $campo_id, $valor) {
        $pdo = Database::getConnection();
        // Verifica se já existe
        $stmt = $pdo->prepare("SELECT id FROM valores_campos_personalizados WHERE colaborador_id = ? AND campo_id = ?");
        $stmt->execute([$colaborador_id, $campo_id]);
        if ($stmt->fetch()) {
            $stmt2 = $pdo->prepare("UPDATE valores_campos_personalizados SET valor = ? WHERE colaborador_id = ? AND campo_id = ?");
            return $stmt2->execute([$valor, $colaborador_id, $campo_id]);
        } else {
            $stmt2 = $pdo->prepare("INSERT INTO valores_campos_personalizados (colaborador_id, campo_id, valor) VALUES (?, ?, ?)");
            return $stmt2->execute([$colaborador_id, $campo_id, $valor]);
        }
    }
}
?>
