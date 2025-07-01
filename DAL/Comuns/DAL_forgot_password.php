<?php
require_once __DIR__ . '/../Database.php';

class DAL_ForgotPassword {
    public function emailExiste($email) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id FROM utilizadores WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() ? true : false;
    }

    public function guardarCodigo($email, $codigo) {
        $db = Database::getConnection();
        // Remove códigos antigos
        $db->prepare("DELETE FROM reset_codigos WHERE email = ?")->execute([$email]);
        // Guarda novo código
        $db->prepare("INSERT INTO reset_codigos (email, codigo, criado_em) VALUES (?, ?, NOW())")->execute([$email, $codigo]);
    }

    public function verificarCodigo($email, $codigo) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM reset_codigos WHERE email = ? AND codigo = ? AND criado_em >= (NOW() - INTERVAL 15 MINUTE)");
        $stmt->execute([$email, $codigo]);
        return $stmt->fetch() ? true : false;
    }

    public function alterarPassword($email, $novaPassword) {
        $db = Database::getConnection();
        // Guardar a password em texto simples (NÃO recomendado em produção)
        $stmt = $db->prepare("UPDATE utilizadores SET password = ? WHERE email = ?");
        $ok = $stmt->execute([$novaPassword, $email]);
        // Remove código após sucesso
        if ($ok) {
            $db->prepare("DELETE FROM reset_codigos WHERE email = ?")->execute([$email]);
        }
        return $ok;
    }
}
?>
