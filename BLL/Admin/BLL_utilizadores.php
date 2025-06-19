<?php
require_once __DIR__ . '/../../DAL/Admin/DAL_utilizadores.php';

class AdminUtilizadoresManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_UtilizadoresAdmin();
    }
    public function getAllUtilizadores() {
        return $this->dal->getAllUtilizadores();
    }
    public function getUtilizadorById($id) {
        return $this->dal->getUtilizadorById($id);
    }
    public function addUtilizador($nome, $username, $email, $perfil_id, $ativo, $password) {
        return $this->dal->addUtilizador($nome, $username, $email, $perfil_id, $ativo, $password);
    }
    public function updateUtilizador($id, $nome, $username, $email, $perfil_id, $ativo) {
        return $this->dal->updateUtilizador($id, $nome, $username, $email, $perfil_id, $ativo);
    }
    public function removeUtilizador($id) {
        return $this->dal->removeUtilizador($id);
    }
    public function updatePassword($id, $password) {
        return $this->dal->updatePassword($id, $password);
    }
    public function getPerfis() {
        return $this->dal->getPerfis();
    }
}
?>
