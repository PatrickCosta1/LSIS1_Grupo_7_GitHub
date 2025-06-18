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
}
?>
