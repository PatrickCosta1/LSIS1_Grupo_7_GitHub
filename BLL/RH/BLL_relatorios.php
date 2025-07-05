<?php
require_once __DIR__ . '/../../DAL/RH/DAL_relatorios.php';

class RHRelatoriosManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_RelatoriosRH();
    }
    public function getIndicadoresGlobais() {
        return $this->dal->getIndicadoresGlobais();
    }
    public function getEquipas() {
        return $this->dal->getEquipas();
    }
    public function getAniversariosPorEquipa($equipaId) {
        return $this->dal->getAniversariosPorEquipa($equipaId);
    }
    // Novo método para obter o nome do colaborador pelo user_id
    public function getNomeColaboradorByUserId($userId) {
        return $this->dal->getNomeColaboradorByUserId($userId);
    }
}
?>