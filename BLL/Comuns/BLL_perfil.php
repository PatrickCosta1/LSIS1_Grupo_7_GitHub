<?php
require_once __DIR__ . '/../../DAL/Comuns/DAL_perfil.php';

class PerfilManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_Perfil();
    }
    public function getUserById($id) {
        return $this->dal->getUserById($id);
    }
    public function updateUserProfile($id, $nome, $email, $username) {
        $result = $this->dal->updateUserProfile($id, $nome, $email, $username);
        if ($result) {
            // Enviar email ao RH (exemplo simples)
            mail('rh@tlantic.com', 'Alteração de dados', "O colaborador $nome ($username) alterou os seus dados.");
        }
        return $result;
    }

    public function alterarPassword($id, $pw_atual, $pw_nova) {
        $pwAtualBD = $this->dal->getPasswordHashById($id);
        if ($pwAtualBD !== $pw_atual) {
            return "A palavra-passe atual está incorreta.";
        }
        $ok = $this->dal->updatePassword($id, $pw_nova);
        return $ok === true ? true : "Erro ao atualizar a palavra-passe.";
    }
}
?>