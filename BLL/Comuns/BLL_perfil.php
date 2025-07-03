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

    // Métodos para o perfil - formações e férias
    public function getColaboradorByUserId($userId) {
        return $this->dal->getColaboradorByUserId($userId);
    }

    public function getFormacoesPorColaborador($colaboradorId) {
        return $this->dal->getFormacoesPorColaborador($colaboradorId);
    }

    public function getPedidosFeriasPorColaborador($colaboradorId) {
        return $this->dal->getPedidosFeriasPorColaborador($colaboradorId);
    }
}
?>