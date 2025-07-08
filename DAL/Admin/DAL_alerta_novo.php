<?php
require_once __DIR__ . '/../Database.php';

class DAL_AlertaNovo {
    public function criarAlerta($tipo, $descricao, $periodicidade_meses, $ativo, $destinatario) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO alertas (tipo, descricao, periodicidade_meses, ativo, destinatario) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$tipo, $descricao, $periodicidade_meses, $ativo, $destinatario]);
    }
}
?>
        return $stmt->execute([$tipo, $descricao, $periodicidade_meses, $ativo]);
    }
}
?>
