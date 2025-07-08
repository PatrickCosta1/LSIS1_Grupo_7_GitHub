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
    // Método para obter o nome do RH (colaborador) pelo user_id
    public function getRHNameByUserId($userId) {
        return $this->dal->getRHNameByUserId($userId);
    }
    public function getAlteracoesContratuais() {
        return $this->dal->getAlteracoesContratuais();
    }
}
?>