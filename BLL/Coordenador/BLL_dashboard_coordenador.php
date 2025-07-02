<?php
require_once __DIR__ . '/../../DAL/Coordenador/DAL_dashboard_coordenador.php';

class CoordenadorDashboardManager {
    private $dal;
    public function __construct() {
        $this->dal = new DAL_DashboardCoordenador();
    }
    public function getCoordenadorName($userId) {
        return $this->dal->getCoordenadorName($userId);
    }
    public function getEquipasByCoordenador($userId) {
        return $this->dal->getEquipasByCoordenador($userId);
    }
    public function getEquipasComMembros($userId) {
        return $this->dal->getEquipasComMembros($userId);
    }
    public function getIdadesColaboradoresPorEquipa($userId) {
        return $this->dal->getIdadesColaboradoresPorEquipa($userId);
    }
    public function getDistribuicaoNivelHierarquico($userId) {
        return $this->dal->getDistribuicaoNivelHierarquico($userId);
    }
    public function getCargosPorNivelHierarquico($userId) {
        return $this->dal->getCargosPorNivelHierarquico($userId);
    }
    public function getTemposNaEmpresaPorEquipa($userId) {
        return $this->dal->getTemposNaEmpresaPorEquipa($userId);
    }
    public function getColaboradoresByEquipa($equipaId) {
        return $this->dal->getColaboradoresByEquipa($equipaId);
    }
}
?>